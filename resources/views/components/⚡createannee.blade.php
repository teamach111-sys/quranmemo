<?php

use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $libelle;
    public $date_debut;
    public $date_fin;
    public $est_en_cours;

    public function store()
    {
        $this->validate([
            'libelle' => 'required|string|max:50',
            'date_debut' => 'required|date|before:date_fin',
            'date_fin' => 'required|date|after:date_debut',
            'est_en_cours' => 'required|boolean',
        ]);

        if ($this->est_en_cours) {
            \App\Models\AnneeScolaire::query()->update(['est_en_cours' => false]);
        }

        \App\Models\AnneeScolaire::create([
            'libelle' => $this->libelle,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'est_en_cours' => $this->est_en_cours,
        ]);
        $this->reset();
        $this->dispatch('annee-cree');
        $this->dispatch('actualiser-annee');
        $this->toast()->success('Création réussie', 'L\'année scolaire a été créée avec succès.')->send();
    }

    public function resetMessage()
    {
        $this->resetValidation();
    }
};

?>

<div class="">
    <h1 class="font-medium text-[18px] my-3">Ajouter une année scolaire</h1>

    <form wire:submit.prevent="store">

        <div class="grid grid-cols-2 w-200 gap-4">



            <div>
                <x-input label="Libellé" type="text" wire:model="libelle" id="libelle" placeholder="Ex: 2026/2027" />
            </div>

            <div>
                <x-input label="Date début" type="date" wire:model="date_debut" id="date_debut" />
            </div>

            <div>
                <x-input label="Date fin" type="date" wire:model="date_fin" id="date_fin" />
            </div>

            <div class="flex items-center gap-2 mt-6">
                <x-checkbox label="Année courante" wire:model="est_en_cours" id="est_en_cours" />
            </div>
        </div>

        <x-button type="submit" wire:dispatch('reset-message') class="mt-4 ">
            <x-codicon-add class="h-5 w-5" /> Ajouter l'Année
        </x-button>
    </form>




</div>
