<?php

use App\Models\AnneeScolaire;
use App\Models\Groupe;
use App\Models\Matiere;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $selectpromo;
    public $selectedannee;
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
        $matieres = DB::table('matieres')->join('niveaux', 'niveaux.id', '=', 'matieres.niveau_id')->join('promotions', 'promotions.niveau_id', '=', 'niveaux.id')->join('programmes', 'programmes.id', '=', 'promotions.programme_id')->select('programmes.nom', 'matieres.nom', 'matieres.id', 'programmes.id')->whereColumn('matieres.annee_etude', '=', 'promotions.annee_etude')->where('programmes.id', $this->selectpromo)->get();
        $groupes = Groupe::with('anneescolaire')->forCurrentAnnee()->get();
        $promotions = Promotion::with('programme', 'anneescolaire')->forCurrentAnnee()->get();
        return view('⚡note', [
            'promotions' => $promotions,
            'groupes' => $groupes,
            'matieres' => $matieres,
        ]);
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
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-3 items-center max-w-300">
                <x-date wire.model.live = "selectdate" />
                <x-select.native wire:model.live="selectpromo" id="selectpromo">
                    <option value="" disabled>Matière</option>
                    @foreach ($matieres as $matiere)
                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                    @endforeach
                </x-select.native>
                <x-select.native wire:model.live="selectpromo" id="selectpromo">
                    <option value="" disabled>Matière</option>
                    @foreach ($matieres as $matiere)
                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                    @endforeach
                </x-select.native>
                <x-select.native wire:model.live="selectpromo" id="selectpromo">
                    <option value="">Sélectionner une promotion</option>
                    @foreach ($promotions as $promo)
                        <option value="{{ $promo->niveau_id }}">{{ $promo->programme->nom }} <p>
                                {{ $promo->annee_etude }}{{ $promo->annee_etude == 1 ? 'er' : 'eme' }} Année
                            </p>
                        </option>
                    @endforeach
                </x-select.native>
                <x-select.native wire:model.live="selectgro" id="selectgro">
                    <option value="">Sélectionner un Groupe</option>
                    @foreach ($groupes as $groupe)
                        <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                    @endforeach
                </x-select.native>

            </div>
            <div>
                <div class="flex gap-3 justify-end items-center">
                    <x-input placeholder="Ajouter une période" />

                    <x-button text="Ajouter" submit />
                </div>
            </div>

        </div>
        <x-table>

        </x-table>
        <div class="mt-3 flex justify-end items-center">
            <x-button text="Imprimer" submit />

        </div>


    </div>
</div>
