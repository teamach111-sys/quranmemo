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

    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

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
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'matiere_nom', 'label' => 'Matière'], ['index' => 'salle', 'label' => 'Salle'], ['index' => 'professeur_nom', 'label' => 'Professeur'], ['index' => 'groupe', 'label' => 'Groupe'], ['index' => 'jour', 'label' => 'Jour'], ['index' => 'heure_debut', 'label' => 'Début'], ['index' => 'heure_fin', 'label' => 'Fin'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => Classe::query()
                ->join('matieres', 'classes.matiere_id', '=', 'matieres.id')
                ->join('users', 'classes.professeur_id', '=', 'users.id')
                ->where('classes.promotion_id', $this->promotion->id)
                ->select('classes.*', 'matieres.nom as matiere_nom', 'users.name as professeur_nom')
                ->when(
                    $this->search,
                    fn($query) => $query
                        ->where('matieres.nom', 'like', "%{$this->search}%")
                        ->orWhere('users.name', 'like', "%{$this->search}%")
                        ->orWhere('classes.salle', 'like', "%{$this->search}%")
                        ->orWhere('classes.groupe', 'like', "%{$this->search}%"),
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
                <div class="w-1/4">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.500ms="search" placeholder="Rechercher..."
                        type="search" />
                </div>
                <div class="flex gap-2">
                    @if (count($selected) > 0)
                        <x-button
                            class="dark:focus:!ring-darkdeletebutton dark:!bg-darkdeletebutton dark:!text-darkcontenttext dark:hover:!bg-darkdeletebuttonhover"
                            x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Classe')) }}', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                    <x-button x-on:click="$tsui.open.modal('createclasse')">
                        <x-codicon-add class="h-5 w-5" />Nouvelle Classe
                    </x-button>
                </div>
            </div>
        </x-slot:header>

        @interact('column_groupe', $row)
            {{ $row->groupe === 'matin' ? 'Matin' : ($row->groupe === 'soir' ? 'Soir' : ($row->groupe === 'apres_midi' ? 'Après-midi' : 'Nuit')) }}
        @endinteract

        @interact('column_jour', $row)
            {{ ucfirst($row->jour) }}
        @endinteract

        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">
                <button type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-500 hover:text-blue-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Éditer
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
