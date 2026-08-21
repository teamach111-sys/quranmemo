<?php

use App\Models\AnneeScolaire;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $selectpromo;
    public $selectgro;
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
        $promotions = Promotion::with('programme', 'anneeScolaire')->forCurrentAnnee()->get();

        return view('⚡suivi', [
            'promotions' => $promotions,
        ]);
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

                    <x-select.native wire:model.live="selectgro" id="selectgro">
                        <option value="">Sélectionner un groupe</option>
                        <option value="lundi">Lundi</option>
                        <option value="mardi">Mardi</option>
                        <option value="mercredi">Mercredi</option>
                        <option value="jeudi">Jeudi</option>
                        <option value="vendredi">Vendredi</option>
                        <option value="samedi">Samedi</option>
                        <option value="dimanche">Dimanche</option>
                    </x-select.native>
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
