<?php

use App\Models\Etudiant;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public int $quantity = 10;
    public ?string $search = '';
    public array $selected = [];

    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

    #[On('refreshetudiants')]
    public function refreshEtudiants()
    {
        $this->resetPage();
        $this->selected = [];
    }
  
    public function with(): array
    {
        return [
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Nom'], ['index' => 'prenom', 'label' => 'Prénom'], ['index' => 'sexe', 'label' => 'Sexe'], ['index' => 'date_naissance', 'label' => 'Date de naissance'], ['index' => 'telephone', 'label' => 'Téléphone'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => Etudiant::query()
                ->when(
                    $this->search,
                    fn($query) => $query
                        ->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('prenom', 'like', "%{$this->search}%")
                        ->orWhere('telephone', 'like', "%{$this->search}%"),
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
                        <x-button class="dark:focus:!ring-darkdeletebutton dark:!bg-darkdeletebutton dark:!text-darkcontenttext dark:hover:!bg-darkdeletebuttonhover"
                            x-on:click="$dispatch('pickid', { class: 'App\\\\Models\\\\Etudiant', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                    <x-button x-on:click="$tsui.open.modal('createetudiant')">
                        Nouveau etudiant
                    </x-button>
                </div>
            </div>
        </x-slot:header>

        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">
                <button
                    x-on:click="$dispatch('pickid', { class: 'App\\\\Models\\\\Etudiant', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
                    type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Supprimer
                </button>
            </div>
        @endinteract

    </x-table>

    <x-modal id="createetudiant" persistent center>
        <livewire:createetudiant />
    </x-modal>

    <x-modal id="deletedata" center class="dark:!bg-black">
        <livewire:suppmodal />
    </x-modal>
</div>
