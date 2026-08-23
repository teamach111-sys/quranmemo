<?php
use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;
new class extends Component
{
    public $selectpromo;
    public $selectmatiere;
    public $selectgro;
    public $selectdate;
    public $selectedannee;
    public int $quantity = 10;
    public ?string $search = '';
    public array $selected = [];
    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];
    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }
    #[On('anneeChanged')]
    public function onChangeAnnee($id)
    {
        $this->selectedannee = $id;
        $this->selectpromo = null;
    }
    public function render()
    {
        $matieres = DB::table('matieres')
            ->join('niveaux', 'niveaux.id', '=', 'matieres.niveau_id')
            ->join('promotions', 'promotions.niveau_id', '=', 'niveaux.id')
            ->join('programmes', 'programmes.id', '=', 'promotions.programme_id')
            ->select('programmes.nom as programme_nom', 'matieres.nom', 'matieres.id', 'programmes.id as programme_id')
            ->whereColumn('matieres.annee_etude', '=', 'promotions.annee_etude')
            ->where('programmes.id', $this->selectpromo)
            ->get();
        $groupes = Groupe::with('anneescolaire')->forCurrentAnnee()->get();
        $promotions = Promotion::with('programme', 'anneescolaire')->forCurrentAnnee()->get();
        return view('⚡note', [
            'promotions' => $promotions,
            'groupes' => $groupes,
            'matieres' => $matieres,
        ]);
    }
    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'id', 'label' => '#'],
                ['index' => 'promotion_id', 'label' => 'Promotion'],
                ['index' => 'period', 'label' => 'Période'],
                ['index' => 'etudiant_id', 'label' => 'Etudiant'],
                ['index' => 'note', 'label' => 'Note'],
                ['index' => 'observation', 'label' => 'Observation'],
            ],
            'rows' => Note::with(['promotion', 'etudiant'])
                ->when(
                    $this->search,
                    fn ($query) => $query->whereHas('etudiant', function ($q) {
                        $q->where('nom', 'like', "%{$this->search}%")
                            ->orWhere('prenom', 'like', "%{$this->search}%");
                    })
                )
                ->orderBy(...array_values($this->sort))
                ->paginate($this->quantity)
                ->withQueryString(),
        ];
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
            <div class="flex gap-2"></div>
        </div>
    </div>
    <div class="mt-5">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
            <div class="flex flex-wrap gap-3 items-center">
                <div class="w-48">
                    <x-date wire:model.live="selectdate" class="w-full" />
                </div>
                <div class="w-48">
                    <x-select.native wire:model.live="selectmatiere" id="selectmatiere" class="w-full">
                        <option value="" disabled selected>Matière</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                        @endforeach
                    </x-select.native>
                </div>
                <div class="w-48">
                    <x-select.native wire:model.live="selectmatiere" id="selectmatiere2" class="w-full">
                        <option value="" disabled selected>Période</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                        @endforeach
                    </x-select.native>
                </div>
                <div class="w-56">
                    <x-select.native wire:model.live="selectpromo" id="selectpromo" class="w-full">
                        <option value="" selected>Sélectionner une promotion</option>
                        @foreach ($promotions as $promo)
                            <option value="{{ $promo->niveau_id }}">
                                {{ $promo->programme->nom }} - {{ $promo->annee_etude }}{{ $promo->annee_etude == 1 ? 'er' : 'eme' }} Année
                            </option>
                        @endforeach
                    </x-select.native>
                </div>
                <div class="w-48">
                    <x-select.native wire:model.live="selectgro" id="selectgro" class="w-full">
                        <option value="" selected>Sélectionner un Groupe</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                        @endforeach
                    </x-select.native>
                </div>
            </div>
            <div class="flex gap-3 justify-end items-center">
                <x-input placeholder="Ajouter une période" class="w-48" />
                <x-button text="Ajouter" submit />
            </div>
        </div>
        <x-table>
        </x-table>
        <div class="mt-3 flex justify-end items-center">
            <x-button text="Imprimer" submit />
        </div>
    </div>
</div>