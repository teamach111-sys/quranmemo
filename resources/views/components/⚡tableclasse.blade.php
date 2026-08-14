<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Classe;
use App\Models\Promotion;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public Promotion $promotion;
    public ?string $search = '';
    public int $quantity = 10;
    public array $selected = [];
    public $activeAnneeId = '';
    public $selectedDay = '';
    public $selectedGroupe = '';
    public $selectedSalle = '';
    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];
    public function boot()
    {
        if (empty($this->activeAnneeId)) {
            $this->activeAnneeId = session('active_annee_id', function () {
                return \App\Models\AnneeScolaire::where('est_en_cours', true)->first()?->id;
            });
        }
    }

    #[\Livewire\Attributes\On('global-annee-changed')]
    public function updateClasseFilter($filterId)
    {
        $this->activeAnneeId = $filterId;
        $this->resetPage();
    }
    #[\Livewire\Attributes\On('refreshClasse')]
    public function refreshClasse()
    {
        $this->resetPage();
        $this->selected = [];
    }

    public function with(): array
    {
        return [
            'promotion' => $this->promotion,
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'matiere_nom', 'label' => 'Matière'], ['index' => 'salle', 'label' => 'Salle'], ['index' => 'professeur_nom', 'label' => 'Professeur'], ['index' => 'jour', 'label' => 'Jour'], ['index' => 'groupe', 'label' => 'Groupe'], ['index' => 'heure_debut', 'label' => 'Début'], ['index' => 'heure_fin', 'label' => 'Fin'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => Classe::query()
                ->join('matieres', 'classes.matiere_id', '=', 'matieres.id')
                ->join('users', 'classes.professeur_id', '=', 'users.id')
                ->join('promotions', 'classes.promotion_id', '=', 'promotions.id')
                ->where('classes.promotion_id', $this->promotion->id)
                ->select('classes.*', 'matieres.nom as matiere_nom', 'users.name as professeur_nom', 'promotions.annee_scolaire_id as annee_scolaire_id')
                ->orderByRaw("CASE LOWER(jour) WHEN 'lundi' THEN 1 WHEN 'mardi' THEN 2 WHEN 'mercredi' THEN 3 WHEN 'jeudi' THEN 4 WHEN 'vendredi' THEN 5 WHEN 'samedi' THEN 6 WHEN 'dimanche' THEN 7 ELSE 8 END")
                ->orderBy('heure_debut','ASC')
                ->when($this->activeAnneeId, fn($query) => $query->where('promotions.annee_scolaire_id', $this->activeAnneeId))
                ->when($this->selectedDay || $this->selectedGroupe || $this->selectedSalle, function ($query) {
                    if ($this->selectedDay) {
                        $query->where('jour', $this->selectedDay);
                    }
                    if ($this->selectedGroupe) {
                        $query->where('groupe', $this->selectedGroupe);
                    }
                    if ($this->selectedSalle) {
                        $query->where('salle', $this->selectedSalle);
                    }
                })

                ->when(
                    $this->search,
                    fn($query) => $query->where(function ($q) {
                        $q->where('matieres.nom', 'like', "%{$this->search}%")
                            ->orWhere('users.name', 'like', "%{$this->search}%")
                            ->orWhere('jour', 'like', "%{$this->search}%")
                            ->orWhere('heure_debut', 'like', "%{$this->search}%")
                            ->orWhere('heure_fin', 'like', "%{$this->search}%")
                            ->orWhere('salle', 'like', "%{$this->search}%")
                            ->orWhere('groupe', 'like', "%{$this->search}%");
                    }),
                )
                ->orderBy(...array_values($this->sort))
                ->paginate($this->quantity)
                ->withQueryString(),
        ];
    }
};
?>

<div>

    <div class="mb-5">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-[20px]">
                    {{ $promotion->programme->nom }}
                    <span class="text-[14px] text-gray-500 dark:text-darksmalltext">
                        {{ $promotion->niveau->nom }} •
                        {{ $promotion->annee_etude }}{{ $promotion->annee_etude == 1 ? 'ère' : 'ème' }} Année
                    </span>
                </h2>
            </div>
        </div>
    </div>

    <x-table selectable wire:model.live="selected" :$headers :$rows :$sort paginate>
        <x-slot:header>
            <div class="flex items-end justify-between mb-4 gap-4">
                <div class="w-fit flex gap-3">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.500ms="search" placeholder="Rechercher..."
                        type="search" />
                    <x-select.native wire:model.live="selectedDay" id="selectedDay">
                        <option value="">Sélectionner un jour</option>
                        <option value="lundi">Lundi</option>
                        <option value="mardi">Mardi</option>
                        <option value="mercredi">Mercredi</option>
                        <option value="jeudi">Jeudi</option>
                        <option value="vendredi">Vendredi</option>
                        <option value="samedi">Samedi</option>
                        <option value="dimanche">Dimanche</option>

                    </x-select.native>
                    <x-select.native wire:model.live="selectedGroupe" id="selectedGroupe">
                        <option value="">Sélectionner un groupe</option>
                        @foreach (\App\Models\Groupe::all() as $groupe)
                            <option value="{{ $groupe->nom }}">{{ $groupe->nom }}</option>
                        @endforeach
                        


                    </x-select.native>

                    <x-select.native wire:model.live="selectedSalle" id="selectedSalle">
                        <option value="">Sélectionner une salle</option>
                        @foreach (\App\Models\Salle::all() as $salle)
                            <option value="{{ $salle->nom }}">{{ $salle->nom }}</option>
                        @endforeach


                    </x-select.native>
                </div>
                <div class="flex gap-2">
                    @if (count($selected) > 0)
                        <x-button
                            class="dark:focus:!ring-darkdeletebutton dark:!bg-darkdeletebutton dark:!text-darkcontenttext dark:hover:!bg-darkdeletebuttonhover"
                            x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Classe')) }}', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                    @if($selectedGroupe)
                    <x-button
                        x-on:click="window.location.href = '{{ route('emploit', ['promotion' => $promotion, 'groupe' => $selectedGroupe]) }}'">
                        <x-elemplus-printer class="h-5 w-5" />Imprimer
                    </x-button>
                    @endif
                    <x-button x-on:click="$tsui.open.modal('createclasse')">
                        <x-codicon-add class="h-5 w-5" />Nouvelle Classe
                    </x-button>

                </div>
            </div>
        </x-slot:header>

        

        @interact('column_jour', $row)
            {{ ucfirst($row->jour) }}
        @endinteract

        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">
                
                <button type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-500 hover:text-blue-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Modifier
                </button>
                <button
                    x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Classe')) }}', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
                    type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Supprimer
                </button>
            </div>
        @endinteract

    </x-table>

    <x-modal id="deletedata" center>
        <livewire:suppmodal />
    </x-modal>
    <x-modal id="createclasse" center>
        <livewire:createclasse :promotion="$promotion" />
    </x-modal>
</div>
