<?php

use Livewire\Component;
use App\Models\Salle;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $nom;
    public $capacite;

    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
        ]);
        Salle::create([
            'nom' => $this->nom,
            'capacite' => $this->capacite,
        ]);
        $this->reset();
        $this->toast()->success('Création réussie', 'La salle a été créée avec succès.')->send();
    }
};
?>

<div class="w-200">
    <h1 class="font-medium text-[18px] my-3">Ajouter une salle</h1>

    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
          

            <x-input type="text" label="Nom de la salle" wire:model="nom" placeholder="Ex: 101, 102, etc" id="salle" />

            

    

            <x-input type="number" label="Capacité" wire:model="capacite" id="capacite" />

        </div>
        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la classe
            </x-button>

          
        </div>
    </form>

</div>
