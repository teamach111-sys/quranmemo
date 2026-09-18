<?php
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Hizb;
use App\Models\Juz;
use App\Models\Promotion;
use App\Models\Sourate;
use App\Models\Suivi;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions, WithPagination, WithoutUrlPagination;
    public int $quantity = 10;
    public array $sort = [
        'column' => 'id',
        'direction' => 'desc',
    ];

    public $selectpromo;
    public $selectedgroupe;
    public $selectedannee;
    public $selectdate;

    public array $sourate = [];
    public array $debut = [];
    public array $fin = [];
    public array $juz = [];
    public array $hizb = [];
    public array $etat = [];
    public array $observation = [];

    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
        $this->selectdate = now()->toDateString();
    }

    #[On('anneeChanged')]
    public function onAnneeChanged($id)
    {
        $this->selectedannee = $id;
        $this->selectpromo = null;
        $this->selectedgroupe = null;
        $this->resetFields();
    }

    public function updatedSelectpromo()
    {
        $this->selectedgroupe = null;
        $this->loadSuivis();
        $this->resetPage();
    }

    public function updatedSelectedgroupe()
    {
        $this->loadSuivis();
        $this->resetPage();
    }

    public function updatedSelectdate()
    {
        $this->loadSuivis();
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->sourate = [];
        $this->debut = [];
        $this->fin = [];
        $this->juz = [];
        $this->hizb = [];
        $this->etat = [];
        $this->observation = [];
    }

    public function loadSuivis()
    {
        $this->resetFields();

        if (!$this->selectpromo || !$this->selectdate || !$this->selectedannee) {
            return;
        }

        $records = Suivi::whereIn('etudiant_id', $this->studentIds())
            ->where('date', $this->selectdate)
            ->where('annee_scolaire_id', $this->selectedannee)
            ->get();

        foreach ($records as $record) {
            $id = $record->etudiant_id;
            $this->sourate[$id] = $record->sourate_id;
            $this->debut[$id] = $record->debut_aya;
            $this->fin[$id] = $record->fin_aya;
            $this->juz[$id] = $record->juz_id;
            $this->hizb[$id] = $record->hizb_id;
            $this->etat[$id] = $record->etat_de_recitation;
            $this->observation[$id] = $record->observation;
        }
    }

    private function studentIds()
    {
        if (!$this->selectpromo) {
            return collect();
        }

        return Etudiant::where('promotion_id', $this->selectpromo)
            ->when($this->selectedgroupe, fn($query) => $query->where('groupe_id', $this->selectedgroupe))
            ->pluck('id');
    }

    public function save()
    {
        if (!$this->selectpromo || !$this->selectdate || !$this->selectedannee) {
            $this->toast()->error('Sélectionnez une promotion et une date avant d\'enregistrer.')->send();

            return;
        }

        $studentIds = $this->studentIds();

        Suivi::whereIn('etudiant_id', $studentIds)
            ->where('date', $this->selectdate)
            ->where('annee_scolaire_id', $this->selectedannee)
            ->forceDelete();

        $saved = 0;
        foreach ($studentIds as $id) {
            $sourateId = $this->sourate[$id] ?? null;
            if (!$sourateId) {
                continue;
            }

            Suivi::create([
                'date' => $this->selectdate,
                'classe_id' => null,
                'etudiant_id' => $id,
                'isArchived' => false,
                'observation' => $this->observation[$id] ?? null,
                'sourate_id' => $sourateId,
                'debut_aya' => $this->debut[$id] ?? null,
                'fin_aya' => $this->fin[$id] ?? null,
                'juz_id' => $this->juz[$id] ?? null,
                'hizb_id' => $this->hizb[$id] ?? null,
                'annee_scolaire_id' => $this->selectedannee,
                'etat_de_recitation' => $this->etat[$id] ?? 'en_cours',
            ]);

            $saved++;
        }

        $this->toast()->success('Suivis enregistrés : ' . $saved . ' étudiant(s).')->send();
    }

    public function render()
    {
        $promotions = Promotion::with('programme', 'anneeScolaire')->forCurrentAnnee()->get();
        $groupes = $this->selectpromo
            ? Classe::where('promotion_id', $this->selectpromo)->with('groupe')->get()->unique('groupe_id')
            : collect();

        return view('⚡suivi', [
            'promotions' => $promotions,
            'groupes' => $groupes,
            'sourates' => Sourate::orderBy('number')->get(),
            'juzs' => Juz::orderBy('number')->get(),
            'hizbs' => Hizb::orderBy('number')->get(),
            'etats' => Suivi::ETATS,
        ]);
    }

    public function with()
    {
        $query = Etudiant::where('promotion_id', $this->selectpromo)
            ->when($this->selectedgroupe, fn($qq) => $qq->where('groupe_id', $this->selectedgroupe));

        return [
            'headers' => [
                ['index' => 'id', 'label' => '#'],
                ['index' => 'nom', 'label' => 'Etudiant'],
                ['index' => 'sourate', 'label' => 'Sourate', 'sortable' => false],
                ['index' => 'debut', 'label' => 'Début', 'sortable' => false],
                ['index' => 'fin', 'label' => 'Fin', 'sortable' => false],
                ['index' => 'juz', 'label' => 'Juz', 'sortable' => false],
                ['index' => 'hizb', 'label' => 'Hizb', 'sortable' => false],
                ['index' => 'etat_de_recitation', 'label' => 'Etat de récitation', 'sortable' => false],
                ['index' => 'observation', 'label' => 'Observation', 'sortable' => false],
            ],
            'rows' => $query->when($this->selectpromo, fn($q) => $q->orderBy(...array_values($this->sort)))->paginate($this->quantity)->withQueryString(),
        ];
    }
};
?>
<x-slot:title>
    {{ __('Suivi Pédagogique') }}
</x-slot:title>
<div>
    <div class="flex flex-col gap-3">
        <div class="flex justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px]">Suivi Pédagogique</h1>
            <div class="flex gap-2"></div>
        </div>
    </div>
    <div class="mt-5">
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <x-date wire:model.live="selectdate" />
            <x-select.native wire:model.live="selectpromo" id="selectpromo">
                <option value="">Sélectionner une promotion</option>
                @foreach ($promotions as $promo)
                    <option value="{{ $promo->id }}">{{ $promo->programme->nom }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedgroupe" id="selectedgroupe">
                <option value="">Sélectionner un groupe</option>
                @foreach ($groupes as $classe)
                    @if ($classe->groupe)
                        <option value="{{ $classe->groupe->id }}">{{ $classe->groupe->nom }}</option>
                    @endif
                @endforeach
            </x-select.native>
        </div>
        <x-table :$headers :$rows :$sort paginate>
            @interact('column_nom', $row)
                <span class="whitespace-nowrap">{{ $row->prenom }} {{ $row->nom }}</span>
            @endinteract
            @interact('column_sourate', $row, $sourates, $sourate)
                <x-select.native wire:model="sourate.{{ $row->id }}" wire:key="sourate-{{ $row->id }}">
                    <option value="">Sourate</option>
                    @foreach ($sourates as $s)
                        <option value="{{ $s->id }}" @selected(($sourate[$row->id] ?? null) == $s->id)>{{ $s->number }}. {{ $s->name_simple }}</option>
                    @endforeach
                </x-select.native>
            @endinteract
            @interact('column_debut', $row)
                <x-input type="number" min="1" wire:model="debut.{{ $row->id }}" wire:key="debut-{{ $row->id }}" placeholder="Aya" />
            @endinteract
            @interact('column_fin', $row)
                <x-input type="number" min="1" wire:model="fin.{{ $row->id }}" wire:key="fin-{{ $row->id }}" placeholder="Aya" />
            @endinteract
            @interact('column_juz', $row, $juzs, $juz)
                <x-select.native wire:model="juz.{{ $row->id }}" wire:key="juz-{{ $row->id }}">
                    <option value="">Juz</option>
                    @foreach ($juzs as $j)
                        <option value="{{ $j->id }}" @selected(($juz[$row->id] ?? null) == $j->id)>Juz {{ $j->number }}</option>
                    @endforeach
                </x-select.native>
            @endinteract
            @interact('column_hizb', $row, $hizbs, $hizb)
                <x-select.native wire:model="hizb.{{ $row->id }}" wire:key="hizb-{{ $row->id }}">
                    <option value="">Hizb</option>
                    @foreach ($hizbs as $h)
                        <option value="{{ $h->id }}" @selected(($hizb[$row->id] ?? null) == $h->id)>Hizb {{ $h->number }}</option>
                    @endforeach
                </x-select.native>
            @endinteract
            @interact('column_etat_de_recitation', $row, $etats, $etat)
                <x-select.native wire:model="etat.{{ $row->id }}" wire:key="etat-{{ $row->id }}">
                    <option value="">Etat</option>
                    @foreach ($etats as $value => $label)
                        <option value="{{ $value }}" @selected(($etat[$row->id] ?? null) == $value)>{{ $label }}</option>
                    @endforeach
                </x-select.native>
            @endinteract
            @interact('column_observation', $row)
                <x-input type="text" wire:model="observation.{{ $row->id }}" wire:key="observation-{{ $row->id }}" placeholder="Observation" />
            @endinteract
        </x-table>
        <div class="mt-3 flex justify-end items-center gap-3">
            <x-button text="Imprimer" x-on:click="window.print()" />
            <x-button text="Enregistrer" wire:click="save" />
        </div>
    </div>
</div>