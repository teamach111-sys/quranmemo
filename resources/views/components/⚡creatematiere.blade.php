<?php

use Livewire\Component;

new class extends Component {
    public $nom;
    public $niveauid;
    public $description;
    public $annee_etude;
    public $nombre_annees;
    public $niveau;
    public function mount(\App\Models\Niveau $niveau)
    {
        $this->niveauid = $niveau->id;
        $this->niveau = $niveau;
    }

    public function resetval(){
        $this->resetValidation();
    }
    public function store()
    {
        $this->validate([
           'nom' => 'required',
           'annee_etude' => 'required',
    ]);
        \App\Models\Matiere::create([
            'nom' => $this->nom,
            'niveau_id' => $this->niveauid,
            'description' => $this->description,
            'annee_etude' => $this->annee_etude,
        ]);
        $this->dispatch('refreshmatiere');
    }
    public function render()
    {
        return view('⚡creatematiere', [
            'niveaux' => \App\Models\Niveau::all(),
        ]);
    }
};
?>

<div class="w-200">
    <form class="flex flex-col gap-4 " wire:submit.prevent="store" wire:loading.class="opacity-50 cursor-not-allowed">

        


        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input label="Nom" placeholder="Ex: Gestion de production" wire:model="nom" />
            </div>

            <div>
                <x-input label="Description" placeholder="Description de la matière" wire:model="description" />
            </div>
            <div>
                <x-select.native label="Année d'étude" wire:model="annee_etude" id="annee_etude">
                    <option value="">Choisir l'année d'étude</option>
                    @for ($i = 1; $i <= $niveau->nombre_annees; $i++)
                        <option 
                            value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'ère' : 'ème' }} année</option>
                    @endfor
                </x-select.native>
            </div>


        </div>

        <div class="flex gap-3 pt-4 w-full">
           <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la matière 
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('creatematiere'); $wire.resetval()"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>


        </div>

    </form>
</div>
