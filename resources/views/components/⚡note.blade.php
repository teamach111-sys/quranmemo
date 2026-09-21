<?php
use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions, WithPagination, WithoutUrlPagination;
    public int $quantity = 10;
    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

    public $selectedpromotion;
    public $selectedperiode;
    public $selectedmatiere;
    public $selectedgroupe;
    public $selectedannee;
    public array $selected = [];
    public array $notes = [];
    public array $observations = [];

    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }

    #[On('anneeChanged')]
    public function onChangeAnnee($id)
    {
        $this->selectedannee = $id;
        $this->selectedpromotion = null;
        $this->selectedperiode = null;
        $this->selectedmatiere = null;
        $this->selectedgroupe = null;
        $this->selected = [];
        $this->notes = [];
        $this->observations = [];
        $this->resetPage();
    }

    public function updatedSelectedpromotion()
    {
        $this->selectedmatiere = null;
        $this->selectedgroupe = null;
        $this->resetPage();
        $this->loadNotes();
    }

    public function updatedSelectedperiode()
    {
        $this->selectedmatiere = null;
        $this->resetPage();
        $this->loadNotes();
    }

    public function updatedSelectedmatiere()
    {
        $this->resetPage();
        $this->loadNotes();
    }

    public function updatedSelectedgroupe()
    {
        $this->resetPage();
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = [];
        $this->observations = [];

        if (!$this->selectedpromotion || !$this->selectedperiode || !$this->selectedmatiere) {
            return;
        }

        $currentPageStudents = Etudiant::where('promotion_id', $this->selectedpromotion)
            ->forCurrentAnnee()
            ->when($this->selectedgroupe, fn ($q) => $q->where('groupe_id', $this->selectedgroupe))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity, ['*'], 'page', request()->get('page', 1))
            ->pluck('id');

        $existingNotes = Note::where('promotion_id', $this->selectedpromotion)
            ->where('matiere_id', $this->selectedmatiere)
            ->where('periode_id', $this->selectedperiode)
            ->whereIn('etudiant_id', $currentPageStudents)
            ->get();

        foreach ($existingNotes as $note) {
            $this->notes[$note->etudiant_id] = $note->note;
            $this->observations[$note->etudiant_id] = $note->observation;
        }
    }

    public function with(): array
    {
        $query = Etudiant::query()->whereRaw('1 = 0');

        if ($this->selectedpromotion && $this->selectedperiode && $this->selectedmatiere) {
            $query = Etudiant::where('promotion_id', $this->selectedpromotion)
                ->forCurrentAnnee()
                ->when($this->selectedgroupe, fn ($q) => $q->where('groupe_id', $this->selectedgroupe))
                ->orderBy(...array_values($this->sort));
        }

        return [
            'headers' => [
                ['index' => 'nom', 'label' => 'Nom'],
                ['index' => 'prenom', 'label' => 'Prénom'],
                ['index' => 'note', 'label' => 'Note', 'sortable' => false],
                ['index' => 'observation', 'label' => 'Observation', 'sortable' => false],
            ],
            'rows' => $query->paginate($this->quantity)->withQueryString(),
        ];
    }

    public function save()
    {
        if (!$this->selectedmatiere || !$this->selectedperiode || !$this->selectedpromotion) {
            $this->toast()->error('Sélectionnez une promotion, une période et une matière avant d\'enregistrer.')->send();

            return;
        }

        foreach ($this->notes as $etudiant_id => $noteValue) {
            if ($noteValue !== '' && $noteValue !== null) {
                Note::updateOrCreate(
                    [
                        'etudiant_id' => $etudiant_id,
                        'matiere_id' => $this->selectedmatiere,
                        'periode_id' => $this->selectedperiode,
                        'promotion_id' => $this->selectedpromotion,
                    ],
                    [
                        'note' => $noteValue,
                        'observation' => $this->observations[$etudiant_id] ?? null,
                    ]
                );
            }
        }

        $this->toast()->success('Notes enregistrées avec succès.')->send();
    }

    public function render()
    {
        $selectedpromotion = $this->selectedpromotion;
        $selectedperiode = $this->selectedperiode;

        return view('⚡note', [
            'periodes' => $selectedperiode,
            'promotions' => $selectedpromotion,
        ]);
    }
};
?>
<x-slot:title>
    {{ __('Notes d\'étudiants') }}
</x-slot:title>
<div>
    <div class="flex flex-col gap-3">
        <div class="flex justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px]">Notes d'étudiants</h1>
        </div>
    </div>
    <div class="mt-5 flex flex-col gap-4">
        <div class="flex flex-wrap items-center gap-4 rounded-[8px]">
            <x-select.native wire:model.live="selectedpromotion" id="selectedpromotion">
                <option value="">Sélectionner une promotion</option>
                @foreach (\App\Models\Promotion::with('programme')->forCurrentAnnee()->get() as $m)
                    <option value="{{ $m->id }}">{{ $m->programme->nom }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedperiode" id="selectedperiode">
                <option value="">Sélectionner une periode</option>
                @foreach (\App\Models\Periode::with('matiere')->get() as $m)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedmatiere" id="selectedmatiere">
                <option value="">Sélectionner une matiere</option>
                @foreach (\App\Models\Classe::with('matiere', 'matiere.periode')->where('promotion_id', $selectedpromotion)->whereHas('matiere', function ($query) use ($selectedperiode) {
        $query->where('periode_id', $selectedperiode);
    })->get()->unique('matiere_id') as $m)
                    <option value="{{ $m->matiere_id }}">{{ $m->matiere?->nom ?? 'no matiere' }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedgroupe" id="selectedgroupe">
                <option value="">Sélectionner un groupe</option>
                @foreach (\App\Models\Classe::with('groupe')->where('promotion_id', $selectedpromotion)->get()->unique('groupe_id') as $m)
                    <option value="{{ $m->groupe_id }}">{{ $m->groupe->nom }}</option>
                @endforeach
            </x-select.native>
        </div>
        <div class="flex flex-wrap items-center gap-3 rounded-[8px]">
            <x-button wire:click="save">
                <x-codicon-save class="h-5 w-5" /> Enregistrer
            </x-button>
        </div>
        <div class="flex flex-wrap items-center gap-3 rounded-[8px]">
            <x-button>
                <x-codicon-desktop-download class="h-5 w-5" /> Exporter Excel
            </x-button>
            <x-button>
                <x-codicon-file-pdf class="h-5 w-5" /> Exporter PDF
            </x-button>
            <x-button>
                <x-codicon-file-symlink-file class="h-5 w-5" /> Télécharger le modèle Excel
            </x-button>
            <x-button>
                <x-codicon-cloud-upload class="h-5 w-5" /> Importer liste des notes
            </x-button>
        </div>
        <div class="rounded-[8px]">
            <x-table selectable wire:model.live="selected" :$headers :$rows :$sort paginate>
                @interact('column_note', $row)
                    <x-input type="number" min="0" max="20" oninput="if (this.value > 20) this.value = 20;" wire:model="notes.{{ $row->id }}" />
                @endinteract
                @interact('column_observation', $row)
                    <x-input type="text" wire:model="observations.{{ $row->id }}" />
                @endinteract
            </x-table>
        </div>
    </div>
</div>