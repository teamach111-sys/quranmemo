<?php
use App\Imports\EtudiantsImport;
use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions, WithFileUploads, WithoutUrlPagination, WithPagination;
    public int $quantity = 10;
    public ?string $search = '';
    public array $selected = [];
    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];
    public $selectedannee;
    public $selectedpromotion = null;
    public $selectedgroupe = null;
    public $importFile = null;
    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }
    #[On('anneeChanged')]
    public function onAnneeChanged($id)
    {
        $this->selectedannee = $id;
        $this->selectedpromotion = null;
        $this->selectedgroupe = null;
    }
    #[On('refreshetudiants')]
    public function refreshEtudiants()
    {
        $this->resetPage();
        $this->selected = [];
    }
    public function importEtudiants()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $anneeId = $this->selectedannee ?? session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');

            $import = new EtudiantsImport(
                anneeScolaireId: (int) $anneeId,
                promotionId: $this->selectedpromotion ? (int) $this->selectedpromotion : null,
                groupeId: $this->selectedgroupe ? (int) $this->selectedgroupe : null,
            );

            Excel::import($import, $this->importFile->getRealPath());

            $this->importFile = null;
            $this->resetPage();
            $this->toast()
                ->success('Importation réussie', 'Les étudiants ont été importés avec succès.')
                ->send();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach (array_slice($failures, 0, 5) as $failure) {
                $errorMessages[] = "Ligne {$failure->row()}: " . implode(', ', $failure->errors());
            }
            $this->toast()
                ->error('Erreur de validation', implode(' | ', $errorMessages))
                ->send();
        } catch (\Exception $e) {
            $this->toast()
                ->error('Erreur', 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage())
                ->send();
        }
    }
    public function with(): array
    {
        $queryy = Etudiant::query()
            ->select('*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) as age')
            ->forCurrentAnnee()
            ->when(
                $this->search,
                fn($query) => $query->where(function ($q) {
                    $q->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('prenom', 'like', "%{$this->search}%")
                        ->orWhere('telephone', 'like', "%{$this->search}%");
                }),
            )
            ->when($this->selectedpromotion, fn($q) => $q->where('promotion_id', $this->selectedpromotion))
            ->when($this->selectedgroupe, fn($q) => $q->where('groupe_id', $this->selectedgroupe));
        return [
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Nom'], ['index' => 'prenom', 'label' => 'Prénom'], ['index' => 'sexe', 'label' => 'Sexe'], ['index' => 'date_naissance', 'label' => 'Date de naissance'], ['index' => 'age', 'label' => 'Age'], ['index' => 'telephone', 'label' => 'Téléphone'], ['index' => 'photo', 'label' => 'Photo', 'sortable' => false], ['index' => 'statut', 'label' => 'Statut'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => $queryy->orderBy(...array_values($this->sort))->paginate($this->quantity)->withQueryString(),
        ];
    }
};
?>
<div>
    <x-table selectable wire:model.live="selected" :$headers :$rows :$sort paginate>
        <x-slot:header>
            <div class="flex flex-col gap-4 mb-4 w-full">
                <div class="flex flex-wrap gap-4 w-full items-center">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.500ms="search" placeholder="Rechercher..."
                        type="search" />
                    <x-select.native wire:model.live="selectedpromotion">
                        <option value="" selected>Selectionner une promotion</option>
                        @foreach (\App\Models\Promotion::with('programme')->forCurrentAnnee()->get() as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->programme->nom }}</option>
                        @endforeach
                    </x-select.native>
                    <x-select.native wire:model.live="selectedgroupe">
                        <option value="" selected>Selectionner un groupe</option>
                        @foreach (\App\Models\Groupe::all() as $groupe)
                            <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                        @endforeach
                    </x-select.native>
                </div>

                <div class="flex flex-wrap gap-2 w-full items-center">
                    <x-button x-on:click="$tsui.open.modal('createetudiant')">
                        <x-codicon-add class="h-5 w-5" /> Nouveau etudiant
                    </x-button>
                    @if (count($selected) > 0)
                        <x-button
                            class="dark:focus:!ring-darkdeletebutton dark:!bg-darkdeletebutton dark:!text-darkcontenttext dark:hover:!bg-darkdeletebuttonhover"
                            x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Etudiant')) }}', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2 w-full items-center">
                    <x-button tag="a"
                        href="{{ route('etudiants.export', ['search' => $search ?? '', 'promotion' => $selectedpromotion ?? '', 'groupe' => $selectedgroupe ?? '']) }}"
                        target="_blank">
                        <x-codicon-desktop-download class="h-5 w-5" /> Exporter Excel
                    </x-button>
                    <x-button tag="a"
                        href="{{ route('etudiants.pdf', ['search' => $search ?? '', 'promotion' => $selectedpromotion ?? '', 'groupe' => $selectedgroupe ?? '']) }}"
                        target="_blank">
                        <x-codicon-file-pdf class="h-5 w-5" /> Exporter PDF
                    </x-button>
                    <x-button tag="a" href="{{ route('etudiants.template') }}" target="_blank">
                        <x-codicon-file-symlink-file class="h-5 w-5" /> Télécharger le modèle Excel
                    </x-button>
                    <x-button x-on:click="$tsui.open.modal('importetudiants')">
                        <x-codicon-cloud-upload class="h-5 w-5" /> Importer etudiants
                    </x-button>
                </div>
            </div>
        </x-slot:header>
        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">
                <button
                    x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Etudiant')) }}', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
                    type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Supprimer
                </button>
                <button
                    x-on:click="$dispatch('editetudiant', { id: {{ $row->id }} }); $tsui.open.modal('editetudiant')"
                    type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-500 hover:text-blue-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Modifier
                </button>
            </div>
        @endinteract
        @interact('column_photo', $row)
            @if($row->photo)
                <x-avatar :image="Storage::url($row->photo)" />
            @else
                <x-avatar text="?" />
            @endif
        @endinteract
        @interact('column_statut', $row)
            @if($row->est_actif)
                <x-badge color="green">Actif</x-badge>
            @else
                <x-badge color="red">Inactif</x-badge>
            @endif
        @endinteract
    </x-table>
    <x-modal id="createetudiant" persistent center>
        <livewire:createetudiant />
    </x-modal>
    <x-modal id="editetudiant" persistent center>
        <livewire:editetudiant />
    </x-modal>
    <x-modal id="deletedata" center class="dark:!bg-black">
        <livewire:suppmodal />
    </x-modal>
    <x-modal id="importetudiants" center>
        <div class="p-4">
            <h2 class="text-lg font-bold mb-4">Importer des étudiants</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Sélectionnez un fichier Excel (.xlsx, .xls) ou CSV contenant les données des étudiants.
                <a href="{{ route('etudiants.template') }}" class="text-blue-500 underline">Télécharger le modèle</a>
            </p>

            @if($selectedpromotion || $selectedgroupe)
                <div class="mb-4 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-sm">
                    <p class="font-medium text-blue-700 dark:text-blue-300">Les étudiants seront importés avec :</p>
                    @if($selectedpromotion)
                        <p class="text-blue-600 dark:text-blue-400">• Promotion :
                            <strong>{{ \App\Models\Promotion::with('programme')->find($selectedpromotion)?->programme?->nom ?? '—' }}</strong>
                        </p>
                    @endif
                    @if($selectedgroupe)
                        <p class="text-blue-600 dark:text-blue-400">• Groupe :
                            <strong>{{ \App\Models\Groupe::find($selectedgroupe)?->nom ?? '—' }}</strong>
                        </p>
                    @endif
                </div>
            @endif

            <x-upload wire:model="importFile" label="Fichier Excel" accept=".xlsx,.xls,.csv" />

            <div class="flex justify-end gap-2 mt-4">
                <x-button x-on:click="$tsui.close.modal('importetudiants')">
                    Annuler
                </x-button>
                <x-button wire:click="importEtudiants" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="importEtudiants">Importer</span>
                    <span wire:loading wire:target="importEtudiants">Importation en cours...</span>
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
