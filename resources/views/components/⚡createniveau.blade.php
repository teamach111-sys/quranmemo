<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Niveau;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $nom;
    public $nombre_annees;
    public $programme_id;
    public $message;
    public $programmes = [];
    public $niveaux = [];

    public function mount()
    {
        // Load all available programmes for dropdown
        $this->programmes = Programme::all();
        $this->getAll();
    }

    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:niveaus',
            'nombre_annees' => 'required|integer|min:1|max:10',
            'programme_id' => 'required|exists:programmes,id',
        ]);

        Niveau::create([
            'nom' => $this->nom,
            'nombre_annees' => $this->nombre_annees,
            'programme_id' => $this->programme_id,
        ]);

        $this->reset();
        $this->dispatch('refreshparent');
        $this->message = 'Le niveau a été créé avec succès';
        $this->getAll();
    }

    public function getAll()
    {
        $this->niveaux = Niveau::with('programme')->get();
    }

    public function delete($id)
    {
        Niveau::find($id)->delete();
        $this->message = 'Le niveau a été supprimé avec succès';
        $this->getAll();
        $this->dispatch('refreshparent');
    }

    public function restore($id)
    {
        Niveau::withTrashed()->find($id)->restore();
        $this->message = 'Le niveau a été restauré avec succès';
        $this->getAll();
        $this->dispatch('refreshparent');
    }

    #[On('reset-message2')]
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

        <!-- Niveau Form Section -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium mb-1">Nom du niveau</label>
                <input type="text" wire:model="nom" id="nom" class="rounded-md border w-full p-2"
                    placeholder="Ex: 1ère année, 2ème année, Licence">
            </div>

            <!-- Nombre d'années -->
            <div>
                <label class="block text-sm font-medium mb-1">Nombre d'années</label>
                <input type="number" wire:model="nombre_annees" id="nombre_annees" min="1" max="10"
                    class="rounded-md border w-full p-2" placeholder="Ex: 1, 2, 3">
            </div>

            <!-- Programme -->
            <div>
                <label class="block text-sm font-medium mb-1">Programme</label>
                <select wire:model="programme_id" id="programme_id" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="">Sélectionnez un
                        programme</option>
                    @foreach ($programmes as $programme)
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100"
                            value="{{ $programme->id }}">
                            {{ $programme->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-darkcontentbg hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Ajouter le niveau
            </button>

            <button type="button" @click="open = false; $wire.dispatch('reset-message2')"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-darkcontentbg hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Fermer
            </button>
        </div>

    </form>

    <!-- Niveaux List Section -->
    @if ($niveaux->count() > 0)
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Liste des niveaux</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-200 dark:bg-slate-700">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">Nom</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Nombre d'années</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Programme</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Statut</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($niveaux as $niveau)
                            <tr class="hover:bg-gray-100 dark:hover:bg-slate-800">
                                <td class="border border-gray-300 px-4 py-2">{{ $niveau->nom }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $niveau->nombre_annees }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $niveau->programme->nom ?? 'N/A' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    @if ($niveau->deleted_at)
                                        <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-sm">Supprimé</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-sm">Actif</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    @if ($niveau->deleted_at)
                                        <button type="button" wire:click="restore({{ $niveau->id }})"
                                            class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                                            Restaurer
                                        </button>
                                    @else
                                        <button type="button" wire:click="delete({{ $niveau->id }})"
                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                                            Supprimer
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">
                                    Aucun niveau trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
