<?php

use Livewire\Component;

new class extends Component {
    public $nom;
    public $description;

  
    public function resetval(){
        $this->resetValidation();
    }
    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        \App\Models\Programme::create([
            'nom' => $this->nom,
            'description' => $this->description,
        ]);

        $this->reset(['nom', 'description']);
        $this->dispatch('refreshfiliere');
    }
};
?>

<div class="w-200">
 
    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
            <div>
                <x-input label="Nom" placeholder="Ex: Developement informatique" wire:model="nom" />
            </div>

             <div>
                <x-input label="Description" placeholder="Description de la filiére" wire:model="description" />
            </div>


        </div>
        <div class="flex gap-3 pt-4 w-full">
          <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la filière
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('createfiliere'); $wire.resetval()"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>


        </div>
    </form>

</div>
