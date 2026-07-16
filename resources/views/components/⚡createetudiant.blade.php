<?php

use App\Models\Etudiant;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

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
    #[On('refreshupload')]
    public function deleteUpload(): void
    {
        $this->photo = null;
    }

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

        Etudiant::create([
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
        return Etudiant::all();
    }

    #[On('reset-message')]
    public function resetMessage()
    {
        $this->message = null;
        $this->resetValidation();
        $this->dispatch('refreshupload');
    }
};
?>

<div class="w-200">
    <form class="flex flex-col gap-4 w-full" wire:submit.prevent="store"
        wire:loading.class="opacity-50 cursor-not-allowed">

      
            @if ($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong>Succès!</strong>
                    <p>{{ $message }}</p>
                </div>
            @endif
      

        <!-- tlmid -->
        <div class="grid grid-cols-3 gap-4">
            <div>
                <x-input label="Nom" placeholder="Nom" wire:model="nom" />
            </div>

            <div>
                <x-input label="Prénom" placeholder="Entrez le prénom" wire:model="prenom" />
            </div>

            <div>
                <x-upload delete label="Photo" delete-method="deleteUpload" wire:model="photo" />
            </div>

            <div>
                <x-select.native label="Sexe" wire:model="sexe">
                    <option value="">Sélectionnez le sexe</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </x-select.native>
            </div>

            <div>
                <x-input type="date" label="Date de naissance" wire:model="date_naissance" />
            </div>

            <div>
                <x-input label="Téléphone" placeholder="Ex: 0678576807" wire:model="telephone" />
            </div>

            <div>
                <x-input type="email" label="Email (Optionnel)" placeholder="exemple@email.com" wire:model="email" />
            </div>

            <div class="col-span-2">
                <x-input label="Adresse (Optionnel)" placeholder="Entrez l'adresse" wire:model="adresse" />
            </div>

            <div class="flex items-center gap-2 mt-6">
                <x-checkbox label="Étudiant actif" wire:model="est_actif" />
            </div>
        </div>

        <div class="border-t pt-4 mt-2">
            <h3 class="font-bold text-lg mb-4">Informations du parent/tuteur (Optionnel)</h3>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <x-input label="Nom du parent" placeholder="Nom du parent" wire:model="parent_nom" />
                </div>

                <div>
                    <x-input label="Téléphone du parent" placeholder="Ex: 064736572" wire:model="parent_telephone" />
                </div>

                <div>
                    <x-select.native label="Relation avec le parent" wire:model="parent_relation">
                        <option value="">Sélectionnez la relation</option>
                        <option value="mere">Mère</option>
                        <option value="pere">Père</option>
                        <option value="tuteur">Tuteur</option>
                        <option value="autre">Autre</option>
                    </x-select.native>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter l'etudiant
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('createetudiant'); $wire.dispatch('reset-message')"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>
        </div>

    </form>
</div>
