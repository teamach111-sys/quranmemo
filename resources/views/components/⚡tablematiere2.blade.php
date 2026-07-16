<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Matiere;

new class extends Component {
    public ?string $search = '';
    public $niveauid;
    public $niveau;

    public function mount($niveau)
    {
        $this->niveauid = $niveau;
        $this->niveau = \App\Models\Niveau::findOrFail($niveau);
    }

    #[On('refreshmatiere')]
    public function refreshTable() {}

    public function with(): array
    {
        $allMatieres = Matiere::query()->where('niveau_id', $this->niveauid)->when($this->search, fn($query) => $query->where('nom', 'like', "%{$this->search}%"))->orderBy('nom')->get();

        $grouped = [];
        for ($i = 1; $i <= $this->niveau->nombre_annees; $i++) {
            $grouped[$i] = $allMatieres->where('annee_etude', $i)->values();
        }

        return [
            'matieresByYear' => $grouped,
            'tableHeaders' => [['index' => 'nom', 'label' => 'Nom'], ['index' => 'action', 'label' => 'Action', 'sortable' => false]],
        ];
    }
};
?>
<x-slot:title>
    {{ __('Gérer les matières') }}
</x-slot:title>
<div>
    <div class="flex flex-col gap-3">
        <div class="flex justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px]">{{ $niveau->nom }}</h1>
        </div>
    </div>

    <div class="mt-5">
        <div class="flex items-end justify-between mb-4 gap-4">
            <div class="w-1/4 grid grid-cols-1 gap-2">
                <x-input icon="magnifying-glass" wire:model.live.debounce.500ms="search" placeholder="Rechercher..."
                    type="search" />
            </div>
            <x-button x-on:click="$tsui.open.modal('creatematiere')">
                Nouvelle matière
            </x-button>
        </div>

        {{-- Grid of x-tables, one per year --}}
        <div class="grid grid-cols-2 gap-4">
            @for ($i = 1; $i <= $niveau->nombre_annees; $i++)
                <x-table :headers="$tableHeaders" :rows="$matieresByYear[$i]"
                    header="{{ $i }} {{ $i == 1 ? 'ère année' : 'ème année' }}">

                    @interact('column_action', $row)
                        <button
                            x-on:click="$dispatch('pickid', { class: 'App\\Models\\Matiere', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
                            type="button"
                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                            Supprimer
                        </button>
                    @endinteract

                </x-table>
            @endfor
        </div>
    </div>

    <x-modal id="deletedata" center>
        <livewire:suppmodal />
    </x-modal>
    <x-modal id="creatematiere" center>
        <livewire:creatematiere :niveau="$this->niveauid" />
    </x-modal>
</div>
