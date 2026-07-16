<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Niveau;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public int $quantity = 10;
    public ?string $search = '';
    public array $selected = [];
    public $filiereId;
    public $filiere;

    public function mount($programme)
    {
        $this->filiereId = $programme;

        $this->filiere = \App\Models\Programme::findOrFail($programme);
    }

    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

    #[On('refreshniveaux')]
    public function refreshNiveaux() {
        $this->resetPage();
        $this->selected = [];
    }
    public function with(): array
    {
        return [
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Nom'], ['index' => 'nombre_annees', 'label' => 'Nombre d\'années'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
            'rows' => Niveau::query()->where('programme_id', $this->filiereId)->when($this->search, fn($query) => $query->where('nom', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%"))->orderBy(...array_values($this->sort))->paginate($this->quantity)->withQueryString(),
        ];
    }
};
?>
<x-slot:title>
    {{ __('Gérer les niveaux') }}
</x-slot:title>
<div>
    <div class="flex flex-col gap-3">
        <div class="flex justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px] ">{{ $filiere->nom }}</h1>
        </div>
    </div>

    <div class="mt-5">
        <x-table selectable wire:model.live="selected" :$headers :$rows :$sort paginate>
            <x-slot:header>
                <div class="flex items-end justify-between mb-4 gap-4">
                    <div class="w-1/4">
                        <x-input icon="magnifying-glass" wire:model.live.debounce.500ms="search"
                            placeholder="Rechercher..." type="search" />
                    </div>
                    <div class="flex gap-2">
                        @if (count($selected) > 0)
                            <x-button
                                class="dark:focus:!ring-darkdeletebutton dark:!bg-darkdeletebutton dark:!text-darkcontenttext dark:hover:!bg-darkdeletebuttonhover"
                                x-on:click="$dispatch('pickid', { class: 'App\\\\Models\\\\Niveau', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                                Supprimer sélectionné ({{ count($selected) }})
                            </x-button>
                        @endif
                        <x-button x-on:click="$tsui.open.modal('createniveau')">
                            Nouveau Niveau
                        </x-button>
                    </div>
                </div>
            </x-slot:header>

            @interact('column_action', $row)
                <div class="flex justify-left gap-4 items-center">
                    <button x-on:click="window.location.href = '{{ route('matiere', $row->id) }}'" type="button"
                        class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                        Gérer les matieres
                    </button>
                    <button
                        x-on:click="$dispatch('pickid', { class: 'App\\\\Models\\\\Niveau', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
                        type="button"
                        class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                        Supprimer
                    </button>
                </div>
            @endinteract

        </x-table>
    </div>

    <x-modal id="deletedata" center>
        <livewire:suppmodal />
    </x-modal>
    <x-modal id="createniveau" center>
        <livewire:createniveau2 :programme="$this->filiereId" />
    </x-modal>





</div>
