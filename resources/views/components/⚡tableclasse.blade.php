<?php

use Livewire\Component;
use App\Models\Classe;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
new class extends Component {
    use WithPagination, withoutUrlPagination;
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
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Nom'], ['index' => 'description', 'label' => 'Description'], ['index' => 'annee_scolaire.libelle', 'label' => 'Année Scolaire'], ['index' => 'professeur_nom', 'label' => 'Professeur'], ['index' => 'programme.nom', 'label' => 'Filière'], ['index' => 'jour', 'label' => 'Jour'], ['index' => 'heure_debut', 'label' => 'Début'], ['index' => 'heure_fin', 'label' => 'Fin'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => Classe::query()
                ->join('users', 'Classes.professeur_id', '=', 'users.id')
                ->select('Classes.*', 'users.name as professeur_nom')
                ->when(
                    $this->search,
                    fn($query) => $query
                        ->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('users.name', 'like', "%{$this->search}%")
                        ->orWhere('jour', 'like', "%{$this->search}%")
                        ->orWhere('heure_debut', 'like', "%{$this->search}%")
                        ->orWhere('heure_fin', 'like', "%{$this->search}%"),
                )
                ->orderBy(...array_values($this->sort))
                ->paginate($this->quantity)
                ->withQueryString(),
        ];
    }
};
?>

<div>
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
                            x-on:click="$dispatch('pickid', { class: 'App\\\\Models\\\\Classe', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                    <x-button x-on:click="$tsui.open.modal('createclasse')">
                        <x-codicon-add class="h-5 w-5" />Nouvelle Classe
                    </x-button>
                </div>
            </div>
        </x-slot:header>

        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">

                <button
                    x-on:click="$dispatch('pickid', { class: 'App\\Models\\Classe', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
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
        <livewire:createclasse />
    </x-modal>
</div>
