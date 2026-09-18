<?php
use App\Models\Etudiant;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions, WithFileUploads;
    public $etudiantId;
    public $nom;
    public $prenom;
    public $photo;
    public $currentPhoto;
    public $sexe;
    public $date_naissance;
    public $telephone;
    public $email;
    public $adresse;
    public $parent_nom;
    public $parent_telephone;
    public $parent_relation;
    public $est_actif;
    public $promo = '';
    public $selectedannee;
    public $groupe;

    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? \App\Models\AnneeScolaire::where('est_en_cours', true)->value('id');
    }

    public function render()
    {
        $groupes = \App\Models\Groupe::all();
        $promotions = \App\Models\Promotion::with('programme')->get();
        return view('⚡editetudiant', [
            'promotions' => $promotions,
            'groupes' => $groupes,
        ]);
    }

    #[On('editetudiant')]
    public function loadEtudiant($id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $this->etudiantId = $etudiant->id;
        $this->nom = $etudiant->nom;
        $this->prenom = $etudiant->prenom;
        $this->sexe = $etudiant->sexe;
        $this->date_naissance = $etudiant->date_naissance;
        $this->telephone = $etudiant->telephone;
        $this->email = $etudiant->email;
        $this->adresse = $etudiant->adresse;
        $this->parent_nom = $etudiant->parent_nom;
        $this->parent_telephone = $etudiant->parent_telephone;
        $this->parent_relation = $etudiant->parent_relation;
        $this->est_actif = $etudiant->est_actif;
        $this->promo = $etudiant->promotion_id ?? '';
        $this->groupe = $etudiant->groupe_id ?? '';
        $this->selectedannee = $etudiant->annee_scolaire_id;
        $this->currentPhoto = $etudiant->photo;
        $this->photo = null;
    }

    #[On('refreshupload-edit')]
    public function deleteUpload(): void
    {
        $this->photo = null;
    }

    public function update()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sexe' => 'required|in:M,F,A',
            'date_naissance' => 'required|date|before:today',
            'telephone' => 'required|string|regex:/^[0-9]{10}$/|unique:etudiants,telephone,' . $this->etudiantId,
            'email' => 'nullable|email',
            'adresse' => 'nullable|string|max:255',
            'parent_nom' => 'nullable|string|max:255',
            'parent_telephone' => 'nullable|string|regex:/^[0-9]{10}$/',
            'parent_relation' => 'nullable|string|max:100',
            'est_actif' => 'nullable|boolean',
        ]);

        $etudiant = Etudiant::findOrFail($this->etudiantId);

        $path = $etudiant->photo;
        if ($this->photo) {
            // Delete old photo if exists
            if ($etudiant->photo && \Storage::disk('public')->exists($etudiant->photo)) {
                \Storage::disk('public')->delete($etudiant->photo);
            }
            $path = $this->photo->store('photos', 'public');
        }

        $etudiant->update([
            'nom' => $this->nom,
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
            'annee_scolaire_id' => $this->selectedannee,
            'promotion_id' => $this->promo ?: null,
            'groupe_id' => $this->groupe ?: null,
            'photo' => $path,
        ]);

        $this->dispatch('refreshetudiants');
        $this->toast()->success('L\'étudiant a été modifié avec succès')->send();
    }

    #[On('reset-edit-message')]
    public function resetMessage()
    {
        $this->resetValidation();
        $this->dispatch('refreshupload-edit');
    }
};
?>
<div class="w-200">
    <form class="flex flex-col gap-4 w-full" wire:submit.prevent="update"
        wire:loading.class="opacity-50 cursor-not-allowed">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <x-input label="Nom" placeholder="Nom" wire:model="nom" />
            </div>
            <div>
                <x-input label="Prénom" placeholder="Entrez le prénom" wire:model="prenom" />
            </div>
            <div class="flex gap-3">
                <div class="flex-1">
                    <x-upload delete label="Photo" delete-method="deleteUpload" wire:model="photo" />
                </div>
                @if ($currentPhoto && !$photo)
                    <div class="pt-[25px]">
                        <x-avatar :image="Storage::url($currentPhoto)" />
                    </div>
                @endif
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
            <div>
                <x-select.native label="Promotion" wire:model="promo">
                    <option value="">Sélectionnez la promotion</option>
                    @foreach ($promotions as $promo)
                        <option value="{{ $promo->id }}">{{ $promo->programme->nom }}</option>
                    @endforeach
                </x-select.native>
            </div>
            <div>
                <x-select.native label="Groupe" wire:model="groupe">
                    <option value="">Sélectionnez un groupe</option>
                    @foreach ($groupes as $groupe)
                        <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                    @endforeach
                </x-select.native>
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
                        <option value="" disabled>Sélectionnez la relation</option>
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
                Modifier l'etudiant
            </x-button>
            <x-button type="button"
                x-on:click="$tsui.close.modal('editetudiant'); $wire.dispatch('reset-edit-message')"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>
        </div>
    </form>
</div>
