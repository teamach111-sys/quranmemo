<?php
use App\Models\AnneeScolaire;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;
new class extends Component {
    public $selectpromo;
    public $selectedgroupe;
    public $selectedannee;
    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }
    #[On('anneeChanged')]
    public function onAnneeChanged($id)
    {
        $this->selectedannee = $id;
        $this->selectpromo = null;
    }
    public function render()
    {
        $promoids = \App\Models\Classe::where('promotion_id', $this->selectpromo)->pluck('id');
        $promotions = Promotion::with('programme', 'anneeScolaire')->forCurrentAnnee()->get();
        return view('⚡suivi', [
            'promotions' => $promotions,
            'promoids'=>$promoids,
        ]);
    }
    public function with()
    {
        $query = \App\Models\Etudiant::where('promotion_id', $this->selectpromo)->when($this->selectedgroupe, fn($queryy) => $queryy->where('groupe_id', $this->selectedgroupe));
        return [
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Etudiant'], ['index' => 'etat_de_recitation', 'label' => 'Etat de récitation']],
            'rows' => $query->get(),
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
        <div class="flex  items-center mb-4">
            <div class>
            </div>
            <div>
                <div class="flex gap-3  items-center">
                    <x-date wire.model.live = "selectdate" />
                    <x-select.native wire:model.live="selectpromo" id="selectpromo">
                        <option value="">Sélectionner une promotion</option>
                        @foreach ($promotions as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->programme->nom }}</option>
                        @endforeach
                    </x-select.native>
                    <x-select.native wire:model.live="selectedgroupe" id="selectedgroupe">
                        <option value="">Sélectionner un groupe</option>
                        @foreach (\App\Models\Classe::with(('groupe'))->whereIn('promotion_id', $promoids)->get()->unique('groupe_id') as $groupe)
                            <option value="{{ $groupe->groupe->id }}">{{ $groupe->groupe->nom }}</option>
                        @endforeach
                    </x-select.native>
                </div>
            </div>
        </div>
        <x-table :$headers :$rows>
        </x-table>
        <div class="mt-3 flex justify-end items-center">
            <x-button text="Imprimer" submit />
        </div>
    </div>
</div>
