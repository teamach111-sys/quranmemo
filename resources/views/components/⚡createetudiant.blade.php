<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
new class extends Component {
    use WithFileUploads;

    public $nom;
    public $prenom;
    public $photo;
    public $sexe;
    public $date_naissance;
    public $telephone;
    public $email;
    public $adresse;
    public $parent_nom;
    public $parent_telephone;
    public $parent_relation;
    public $est_actif;
    public $message;

    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sexe' => 'required|in:M,F,A',
            'date_naissance' => 'required|date|before:today',
            'telephone' => 'required|unique:Etudiants|string|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string|max:255',
            'parent_nom' => 'nullable|string|max:255',
            'parent_telephone' => 'nullable|string|regex:/^[0-9]{10}$/',
            'parent_relation' => 'nullable|string|max:100',
            'est_actif' => 'nullable|boolean',
        ]);

        $path = $this->photo->store('photos', 'public');

        \App\Models\Etudiant::create([
            'nom' => $this->nom,
            'photo' => $path,
            'prenom' => $this->prenom,
            'sexe' => $this->sexe,
            'date_naissance' => $this->date_naissance,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'parent_nom' => $this->parent_nom,
            'parent_telephone' => $this->parent_telephone,
            'parent_relation' => $this->parent_relation,
            'est_actif' => $this->est_actif,
        ]);
        $this->reset();
        $this->dispatch('refreshparent');
        $this->message = 'L\'étudiant a été créé avec succès';
        $this->getAll();
    }
    public function getAll()
    {
        return \App\Models\Etudiant::all();
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

        <!-- tlmid -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium mb-1">Nom</label>
                <input type="text" wire:model="nom" id="nom" class="rounded-md border w-full p-2"
                    placeholder="Entrez le nom">
            </div>

            <!-- Prenom -->
            <div>
                <label class="block text-sm font-medium mb-1">Prénom</label>
                <input type="text" wire:model="prenom" id="prenom" class="rounded-md border w-full p-2"
                    placeholder="Entrez le prénom">
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium mb-1">Photo</label>
                <input type="file" wire:model="photo" id="photo" class="rounded-md border w-full p-2">
            </div>

            <!-- Sexe -->
            <div>
                <label class="block text-sm font-medium mb-1">Sexe</label>
                <select wire:model="sexe" id="sexe" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="">Sélectionnez le sexe</option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="M">Masculin</option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="F">Féminin</option>
                </select>
            </div>

            <!-- Date de naissance -->
            <div>
                <label class="block text-sm font-medium mb-1">Date de naissance</label>
                <input type="date" wire:model="date_naissance" id="date_naissance"
                    class="rounded-md border w-full p-2 ">
            </div>

            <!-- Telephone -->
            <div>
                <label class="block text-sm font-medium mb-1">Téléphone</label>
                <input type="text" wire:model="telephone" id="telephone" class="rounded-md border w-full p-2"
                    placeholder="Ex: 0678576807">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium mb-1">Email (Optionnel)</label>
                <input type="email" wire:model="email" id="email" class="rounded-md border w-full p-2"
                    placeholder="exemple@email.com">
            </div>

            <!-- Adresse -->
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1">Adresse (Optionnel)</label>
                <input type="text" wire:model="adresse" id="adresse" class="rounded-md border w-full p-2"
                    placeholder="Entrez l'adresse">
            </div>

            <!-- Est actif -->
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="est_actif" id="est_actif" class="rounded">
                <label for="est_actif" class="text-sm">Étudiant actif</label>
            </div>
        </div>

        <!-- Parent Section -->
        <div class="border-t pt-4">
            <h3 class="font-bold text-lg mb-4">Informations du parent/tuteur (Optionnel)</h3>

            <div class="grid grid-cols-3 gap-4">
                <!-- Parent Nom -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nom du parent</label>
                    <input type="text" wire:model="parent_nom" id="parent_nom" class="rounded-md border w-full p-2"
                        placeholder="Nom du parent">
                </div>

                <!-- Parent Telephone -->
                <div>
                    <label class="block text-sm font-medium mb-1">Téléphone du parent</label>
                    <input type="text" wire:model="parent_telephone" id="parent_telephone"
                        class="rounded-md border w-full p-2" placeholder="Ex: 064736572">
                </div>

                <!-- Parent Relation -->
                <div>
                    <label class="block text-sm font-medium mb-1">Relation avec le parent</label>
                    <select wire:model="parent_relation" id="parent_relation" class="rounded-md border w-full p-2">
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="">Sélectionnez la relation</option>
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="mere">Mère</option>
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="pere">Père</option>
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="tuteur">Tuteur</option>
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100" value="autre">Autre</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Ajouter l'etudiant
            </button>

            <button  type="button" @click="open = false; $wire.dispatch('reset-message')"
                class="dark:bg-white dark:text-black dark:hover:bg-slate-100
  flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer ">
                Fermer
            </button>
        </div>

    </form>
</div>
