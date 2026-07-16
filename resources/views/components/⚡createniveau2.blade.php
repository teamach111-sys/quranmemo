<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Programme;

new class extends Component {
    public $nom;
    public $nombre_annees;
    public $programme_id;

    public function mount(Programme $programme)
    {
        $this->programme_id = $programme->id;
    }
    
    public function resetval(){
        $this->resetValidation();
    }
    public function store()
    {
        $this->validate([
            'nom' => 'required',
            'nombre_annees' => 'required|max:5',
        ]);
        \App\Models\Niveau::create([
            'nom' => $this->nom,
            'nombre_annees' => $this->nombre_annees,
            'programme_id' => $this->programme_id,
        ]);

        $this->reset(['nom', 'nombre_annees']);
        $this->dispatch('refreshniveaux');
    }
};
?>

<div class="w-200">
    <form class="flex flex-col gap-4 w-full" wire:submit.prevent="store"
        wire:loading.class="opacity-50 cursor-not-allowed">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input label="Nom" placeholder="Ex: Technicien Specialisé" wire:model="nom" />
            </div>

            <div>
                <x-input label="Nombre d'années" placeholder="Ex: 2" wire:model="nombre_annees" />
            </div>


        </div>

        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter le niveau
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('createniveau'); $wire.resetval()"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>
        </div>

    </form>
</div>
