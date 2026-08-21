<?php

use App\Models\AnneeScolaire;
use App\Models\Groupe;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
use TallStackUi\Facades\Toast;
new class extends Component {
    use interactions;

    public $nom;
    public $selectedannee;

    public function mount(){
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }
    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
        ]);
        Groupe::create([
            'nom' => $this->nom,
            'annee_scolaire_id' => $this->selectedannee,
        ]);
        $this->reset();
        $this->toast()->success('Création réussie', 'Le groupe a été créée avec succès.')->send();
    }
};
?>

<div class="w-200">
    <h1 class="font-medium text-[18px] my-3">Ajouter une salle</h1>

    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
          

            <x-input type="text" label="Nom du groupe" wire:model="nom" placeholder="Ex: G1, G2, etc" id="salle" />

            

    


        </div>
        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter le groupe
            </x-button>

          
        </div>
    </form>

</div>
