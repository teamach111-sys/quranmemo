<?php

use Livewire\Component;

new class extends Component {
    public $message = null;
    public $nom;
    public $description;
    public $annee_scolaire_id;
    public $professeur_id;
    public $programme_id;
    public $jour;
    public $heure_debut;
    public $heure_fin;

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
        $this->dispatch('refreshtable');
        $this->message = 'Classe ajoutée avec succès !';
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
                    placeholder="Gestion de production...">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <input type="text" wire:model="description" id="description" class="rounded-md border w-full p-2"
                    placeholder="Description....">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Programme</label>
                <select wire:model="programme_id" id="programme_id" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">
                        Sélectionner un programme</option>
                    @foreach (\App\Models\Programme::all() as $programme)
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                            value="{{ $programme->id }}">{{ $programme->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Année Scolaire</label>
                <select wire:model="annee_scolaire_id" id="annee_scolaire_id" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">
                        Sélectionner une année scolaire</option>
                    @foreach (\App\Models\AnneeScolaire::all() as $annee)
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                            value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Professeur</label>
                <select wire:model="professeur_id" id="professeur_id" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">
                        Sélectionner un professeur</option>
                    @foreach (\App\Models\User::where('role', 'professeur')->get() as $professeur)
                        <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                            value="{{ $professeur->id }}">{{ $professeur->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Jour</label>
                <select wire:model="jour" id="jour" class="rounded-md border w-full p-2">
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">
                        Sélectionner un jour</option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="lundi">Lundi
                    </option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="mardi">Mardi
                    </option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="mercredi">
                        Mercredi</option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="jeudi">Jeudi
                    </option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="vendredi">
                        Vendredi</option>
                    <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="samedi">Samedi
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Heure Début</label>
                <input type="time" wire:model="heure_debut" id="heure_debut" class="rounded-md border w-full p-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Heure Fin</label>
                <input type="time" wire:model="heure_fin" id="heure_fin" class="rounded-md border w-full p-2">
            </div>
        </div>

        <button type="submit" wire:dispatch('reset-message')
            class="mt-5 dark:bg-white dark:text-black dark:hover:bg-slate-100 flex-1 rounded-md bg-[#262626] hover:bg-[#3B3B3B] text-white px-4 py-2 cursor-pointer flex gap-2 items-center ">
            <x-codicon-add class="h-5 w-5" /> Ajouter la Classe
        </button>
    </form>

</div>
