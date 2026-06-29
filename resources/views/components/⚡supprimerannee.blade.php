<?php

use Livewire\Component;

new class extends Component {
    public $message = null;
    public $lesannees = [];
    public $anneeselecte;
    #[\Livewire\Attributes\On('actualiser-annee')]
    public function jibannees1()
    {
        $this->lesannees = \App\Models\AnneeScolaire::orderBy('id', 'desc')->get();
    }
     public function supprimerlannee()
    {
        if ($this->anneeselecte) {
            \App\Models\AnneeScolaire::destroy($this->anneeselecte);
            $this->anneeselecte = null;
            $this->jibannees1();
            $this->dispatch('annee-cree');
            $this->message = 'L\'année scolaire a été supprimé avec succès';
        }
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
};
?>

<div>
    <h1 class="font-medium text-[18px] my-4">Supprimer une année scolaire</h1>
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
    <div class="flex items-center gap-2 w-200">
        <select wire:model="anneeselecte" name="annee_id" id="annee_select"
            class="p-2 border focus:outline-none border-[#E5E5E5] dark:border-[#3E3E3E] h-10 rounded-md w-full">
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">Sélectionner une
                année</option>
            @foreach ($lesannees as $annee)
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                    value="{{ $annee->id }}">
                    {{ $annee->libelle }}
                </option>
            @endforeach
        </select>
        <Button wire:click="supprimerlannee"
            class="w-90 flex items-center gap-2 cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-uiw-delete
                class="w-5 h-5" />Supprimer
            l'Année</Button>


    </div>
</div>
