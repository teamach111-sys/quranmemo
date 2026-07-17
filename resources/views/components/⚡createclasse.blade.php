<?php

use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public $nom;
    public $description;
    public $annee_scolaire_id;
    public $professeur_id;
    public $programme_id;
    public $jour;
    public $heure_debut;
    public $heure_fin;

    public function store()
    {
        $validated = $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->toast()->error('Erreur de validation', 'Veuillez vérifier les informations saisies.')->send();
            }
        })->validate([
            'nom' => 'required|string|max:50',
            'description' => 'nullable|string',
            'annee_scolaire_id' => 'required|integer|exists:annee_scolaires,id',
            'professeur_id' => 'required|integer|exists:users,id',
            'programme_id' => 'required|integer|exists:programmes,id',
            'jour' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        \App\Models\Classe::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'annee_scolaire_id' => $this->annee_scolaire_id,
            'professeur_id' => $this->professeur_id,
            'programme_id' => $this->programme_id,
            'jour' => $this->jour,
            'heure_debut' => $this->heure_debut,
            'heure_fin' => $this->heure_fin,
        ]);

        $this->reset();
        $this->dispatch('refreshClasse');
        
        $this->toast()->success('Création réussie', 'La classe a été créée avec succès.')->send();
    }
};
?>

<div>
   
    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
            <x-input label="Nom" wire:model="nom" id="nom" placeholder="Gestion de production..." />

            <x-input label="Description" wire:model="description" id="description" placeholder="Description...." />

            <x-select.native label="Programme" wire:model="programme_id" id="programme_id">
                <option value="">Sélectionner un programme</option>
                @foreach (\App\Models\Programme::all() as $programme)
                    <option value="{{ $programme->id }}">{{ $programme->nom }}</option>
                @endforeach
            </x-select.native>

            <x-select.native label="Année Scolaire" wire:model="annee_scolaire_id" id="annee_scolaire_id">
                <option value="">Sélectionner une année scolaire</option>
                @foreach (\App\Models\AnneeScolaire::all() as $annee)
                    <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                @endforeach
            </x-select.native>

            <x-select.native label="Professeur" wire:model="professeur_id" id="professeur_id">
                <option value="">Sélectionner un professeur</option>
                @foreach (\App\Models\User::where('role', 'professeur')->get() as $professeur)
                    <option value="{{ $professeur->id }}">{{ $professeur->name }}</option>
                @endforeach
            </x-select.native>

            <x-select.native label="Jour" wire:model="jour" id="jour">
                <option value="">Sélectionner un jour</option>
                <option value="lundi">Lundi</option>
                <option value="mardi">Mardi</option>
                <option value="mercredi">Mercredi</option>
                <option value="jeudi">Jeudi</option>
                <option value="vendredi">Vendredi</option>
                <option value="samedi">Samedi</option>
            </x-select.native>

            <x-input type="time" label="Heure Début" wire:model="heure_debut" id="heure_debut" />

            <x-input type="time" label="Heure Fin" wire:model="heure_fin" id="heure_fin" />
        </div>

        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la classe
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('createclasse')"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>
        </div>
    </form>

</div>