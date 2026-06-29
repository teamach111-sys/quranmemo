<?php

use Livewire\Component;

new class extends Component {
    public $message = null;
    public $libelle;
    public $date_debut;
    public $date_fin;
    public $est_en_cours;
    public $lesannees = [];
    public $anneeselecte;

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
        $this->message = 'L\'année scolaire a été créée avec succès';
    }

    public function resetMessage()
    {
        $this->message = null;
        $this->resetValidation();
    }
    public function mount()
    {
        $this->jibannees1();
    }

    #[\Livewire\Attributes\On('actualiser-annee')]
    public function jibannees1()
    {
        $this->lesannees = \App\Models\AnneeScolaire::orderBy('id', 'desc')->get();
    }

    public function supprimerlannee(){
        if($this->anneeselecte){
            \App\Models\AnneeScolaire::destroy($this->anneeselecte);
            $this->anneeselecte = null;
            $this->jibannees1();
            $this->dispatch('annee-cree');
        }
        
    }
    
};

?>

<div>
    <h1 class="font-medium text-[18px] my-3">Ajouter une année scolaire</h1>
    <div class="w-200 my-3">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong>Erreur!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            @if ($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" x-data
                    x-init="setTimeout(() => $wire.resetMessage(), 3000)">
                    <strong>Succès!</strong>
                    <p>{{ $message }}</p>
                </div>
            @endif
        @endif


    </div>
    <form wire:submit.prevent="store">

        <div class="grid grid-cols-2 w-200 gap-4">



            <!-- Libellé -->
            <div>
                <label class="block text-sm font-medium mb-1">Libellé</label>
                <input type="text" wire:model="libelle" id="libelle" class="rounded-md border w-full p-2"
                    placeholder="Ex: 2026/2027">
            </div>

            <!-- Date début -->
            <div>
                <label class="block text-sm font-medium mb-1">Date début</label>
                <input type="date" wire:model="date_debut" id="date_debut" class="rounded-md border w-full p-2">
            </div>

            <!-- Date fin -->
            <div>
                <label class="block text-sm font-medium mb-1">Date fin</label>
                <input type="date" wire:model="date_fin" id="date_fin" class="rounded-md border w-full p-2">
            </div>

            <!-- Est en cours -->
            <div>
                <label class="block text-sm font-medium mb-1">Année courante</label>
                <input type="checkbox" wire:model="est_en_cours" id="est_en_cours" class="rounded">
            </div>
        </div>

        <button type="submit" wire:dispatch('reset-message')
            class="mt-5 dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer flex gap-2 items-center ">
           <x-codicon-add class="h-5 w-5" /> Ajouter l'Année
        </button>
    </form>
    <h1 class="font-medium text-[18px] my-4">Supprimer une année scolaire</h1>
    <div class="flex items-center gap-2">
        <select wire:model="anneeselecte" name="annee_id" id="annee_select"
            class="p-2 border focus:outline-none border-[#E5E5E5] dark:border-[#3E3E3E] h-10 rounded-md w-55">
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">Sélectionner une année</option>
            @foreach ($lesannees as $annee)
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                    value="{{ $annee->id }}">
                    {{ $annee->libelle }}
                </option>
            @endforeach
        </select>
        <Button wire:click="supprimerlannee"
            class="flex items-center gap-2 cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-uiw-delete
                class="w-5 h-5" />Supprimer
            l'Année</Button>


    </div>




</div>
