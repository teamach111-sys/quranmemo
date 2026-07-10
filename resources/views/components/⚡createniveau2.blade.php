<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Programme;

new class extends Component {

    public $nom;
    public $nombre_annees;
    public $programme_id;
    public $message;

   
    
  
    public function mount(Programme $programme)
    {
        $this->programme_id = $programme->id;
    }

    public function store()
    {
       

        \App\Models\Niveau::create([
            'nom' => $this->nom,
            'nombre_annees' => $this->nombre_annees,
            'programme_id' => $this->programme_id,
        ]);
        
        $this->reset(['nom', 'nombre_annees']);
        $this->dispatch('refreshparent');
        $this->message = 'Le niveau a été créé avec succès';
    }

    #[On('reset-message')]
    public function resetMessage()
    {
        $this->message = null;
        $this->resetValidation();
    }

    
};
?>

<div>
    <form class="flex flex-col gap-4 w-full" wire:submit.prevent="store"
        wire:loading.class="opacity-50 cursor-not-allowed">

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

        <div class="grid grid-cols-2 gap-4">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium mb-1">Nom du niveau</label>
                <input type="text" wire:model="nom" id="nom" class="rounded-md border w-full p-2"
                    placeholder="Entrez le nom (ex: 1ère année)">
            </div>

            <!-- Nombre d'années -->
            <div>
                <label class="block text-sm font-medium mb-1">Nombre d'années</label>
                <input type="number" wire:model="nombre_annees" id="nombre_annees" class="rounded-md border w-full p-2"
                    placeholder="Entrez le nombre d'années" min="1">
            </div>

          
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Ajouter le niveau
            </button>

            <button  type="button" @click="open = false; $wire.dispatch('reset-message')"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Fermer
            </button>
        </div>

    </form>
</div>