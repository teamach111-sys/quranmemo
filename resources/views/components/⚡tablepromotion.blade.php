<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\WithoutUrlPagination;
use App\Models\Promotion;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public ?string $search = '';
    public int $quantity = 10;
    public array $selected = [];

    // Keep the sync intact with the URL bar (?annee=X)
    #[Url(as: 'annee')]
    public $activeAnneeId = '';

    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

    /**
     * Boot runs before mount and on every subsequent hydrate request.
     * This ensures the active year is caught correctly without overwriting URL parameters.
     */
    public function boot()
    {
        if (empty($this->activeAnneeId)) {
            // First look at the persistent session value, then fallback to the current active year
            $this->activeAnneeId = session('active_annee_id', function() {
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

    #[\Livewire\Attributes\On('refreshPromotion')]
    public function refreshPromotion()
    {
        $this->resetPage();
        $this->selected = [];
    }

    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'id', 'label' => '#'], 
                ['index' => 'programme_nom', 'label' => 'Filière'], 
                ['index' => 'niveau_nom', 'label' => 'Niveau'], 
                ['index' => 'annee_etude', 'label' => 'Année Étude'], 
                ['index' => 'action', 'label' => 'Action', 'sortable' => false]
            ],
            'rows' => Promotion::query()
                ->join('programmes', 'promotions.programme_id', '=', 'programmes.id')
                ->join('niveaux', 'promotions.niveau_id', '=', 'niveaux.id')
                ->select('promotions.*', 'programmes.nom as programme_nom', 'niveaux.nom as niveau_nom')
                
                // Enforce the global selected school year restriction safely
                ->when(
                    $this->activeAnneeId, 
                    fn($query) => $query->where('promotions.annee_scolaire_id', $this->activeAnneeId)
                )
                
                // Grouped search check targeting absolute table columns
                ->when(
                    trim($this->search),
                    fn($query) => $query->where(function($q) {
                        $q->where('programmes.nom', 'like', "%{$this->search}%")
                          ->orWhere('niveaux.nom', 'like', "%{$this->search}%")
                          ->orWhere('promotions.annee_etude', 'like', "%{$this->search}%");
                    })
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
                            x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Promotion')) }}', id: {{ json_encode($selected) }} }); $tsui.open.modal('deletedata')">
                            Supprimer sélectionné ({{ count($selected) }})
                        </x-button>
                    @endif
                    <x-button x-on:click="$tsui.open.modal('createpromotion')">
                        <x-codicon-add class="h-5 w-5" />Nouvelle Promotion
                    </x-button>
                </div>
            </div>
        </x-slot:header>
        
        @interact('column_annee_etude', $row)
            {{ $row->annee_etude }}{{ $row->annee_etude == 1 ? 'ère' : 'ème' }} Année
        @endinteract
        
        @interact('column_action', $row)
            <div class="flex justify-left gap-4 items-center">
                <button x-on:click="window.location.href = '{{ route('classes', $row->id) }}'" type="button"
                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-red-500 hover:text-red-700 dark:text-darkcontenttext dark:hover:text-darkcontenttext focus:outline-hidden cursor-pointer">
                    Gérer les classes
                </button>
                <button
                    x-on:click="$dispatch('pickid', { class: '{{ addslashes(deleteClass('Promotion')) }}', id: {{ $row->id }} }); $tsui.open.modal('deletedata')"
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
    <x-modal id="createpromotion" center>
        <livewire:createpromotion />
    </x-modal>
</div>