<?php

use Livewire\Component;

new class extends Component {
    public $message = null;
    public $nom;
    public $description;
    public $nombre_annees;

    #[\Livewire\Attributes\On('reset-message')]
    public function resetMessage()
    {
        $this->message = null;
    }

    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:50',
            'description' => 'nullable|string',
            'nombre_annees' => 'required|integer|min:1',
        ]);

        \App\Models\Programme::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'nombre_annees' => $this->nombre_annees,
        ]);

        $this->reset();
        $this->dispatch('refreshtable');
        $this->message = 'Programme ajouté avec succès !';
    }
};
?>

<div>
    <div class="w-full my-3 ">
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
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nom</label>
                <input type="text" wire:model="nom" id="nom" class="rounded-md border w-full p-2"
                    placeholder="Ex: diplome de ....">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <input type="text" wire:model="description" id="description" class="rounded-md border w-full p-2"
                    placeholder="Description....">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nombre d'Années</label>
                <input type="number" wire:model="nombre_annees" id="nombre_annees" class="rounded-md border w-full p-2"
                    placeholder="Ex: 2">
            </div>
        </div>
        <div class="flex gap-3 pt-4 w-full">
            <button type="submit"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Ajouter la filière
            </button>
              <button  type="button" @click="open = false; $wire.dispatch('reset-message')"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Fermer
            </button>


        </div>
    </form>

</div>
