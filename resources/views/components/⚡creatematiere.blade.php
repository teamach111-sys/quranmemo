<?php

use Livewire\Component;

new class extends Component {
    public $nom;
    public $niveauid;
    public $description;
    public $annee_etude;
    public $message;
    public $nombre_annees = 5;
    public $niveau;
    public function mount(\App\Models\Niveau $niveau)
    {
        $this->niveauid = $niveau->id;
        $this->niveau=$niveau;
        
        
    }

  
    public function resett()
    {
        $this->message = null;
       
    }
    public function store()
    {
        \App\Models\Matiere::create([
            'nom' => $this->nom,
            'niveau_id' => $this->niveauid,
            'description' => $this->description,
            'annee_etude' => $this->annee_etude,
        ]);
        $this->message = 'La matière a été créé avec succès';
        $this->dispatch('refresh-table2');
    }
     public function render()
    {
        return view('⚡creatematiere', [
            'niveaux' => \App\Models\Niveau::all()
        ]);
    }
};
?>

<div>
    <form class="flex flex-col gap-4 " wire:submit.prevent="store" wire:loading.class="opacity-50 cursor-not-allowed">

        <div wire:key="{{ md5($message) }}" x-data x-init="setTimeout(() => $wire.resett(), 4000)">
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
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong>Succès!</strong>
                        <p>{{ $message }}</p>
                    </div>
                @endif
            @endif

        </div>


        <!-- Niveau Form Section -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium mb-1">Nom du matière</label>
                <input type="text" wire:model="nom" id="nom" class="rounded-md border w-full p-2"
                    placeholder="Ex: 1ère année, 2ème année, Licence">
            </div>

            <!-- Nombre d'années -->
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <input type="number" wire:model="description" id="description" min="1" max="10"
                    class="rounded-md border w-full p-2" placeholder="Ex: desc">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Année d'étude</label>
                <select wire:model="annee_etude" id="annee_etude" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">Sélectionnez une année</option>
                    @for ($i = 1; $i <= $niveau->nombre_annees; $i++)
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'ère' : 'ème' }} année</option>
                    @endfor
                </select>
            </div>


        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4 w-full">
            <button type="submit"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Ajouter la matière
            </button>
              <button  type="button" @click="open = false; $wire.dispatch('reset-message')"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Fermer
            </button>


        </div>

    </form>
</div>
