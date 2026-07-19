<?php

use Livewire\Component;
use TallStackUi\Traits\Interactions;
use App\Models\Matiere;
use App\Models\User;

new class extends Component {
    use Interactions;

    public int $promotion_id;
    public $promotion;
    public ?int $matiere_id = null;
    public ?int $professeur_id = null;
    public ?string $groupe;
    public ?string $salle = null;
    public ?string $jour = null;
    public ?string $heure_debut = null;
    public ?string $heure_fin = null;

    public function mount(\App\Models\Promotion $promotion)
    {
        $this->promotion = $promotion;
        $this->promotion_id = $promotion->id;
    }
    #[\Livewire\Attributes\Computed]
    public function filteredMatieres()
    {
        if (!$this->promotion || !$this->promotion->niveau) {
            return collect();
        }

        return Matiere::where('niveau_id', $this->promotion->niveau->id)
            ->where('annee_etude', $this->promotion->annee_etude)
            ->get();
    }

    public function store()
    {
        $this->validate([
            'matiere_id' => 'required|integer|exists:matieres,id',
            'professeur_id' => 'required|integer|exists:users,id',
            'groupe' => 'required|in:matin,soir,nuit',
            'salle' => 'required|string',
            'jour' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        \App\Models\Classe::create([
            'promotion_id' => $this->promotion_id,
            'matiere_id' => $this->matiere_id,
            'professeur_id' => $this->professeur_id,
            'groupe' => $this->groupe,
            'salle' => $this->salle,
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

<div class="w-200">

    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
            <x-select.native wire:model="matiere_id" label="Matière" id="matiere_id">
                <option value="">Choisir la Matière</option>
                @foreach ($this->filteredMatieres as $matiere)
                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                @endforeach
            </x-select.native>

            <x-select.native wire:model="groupe" label="Groupe" id="groupe">
                <option value="matin">Matin</option>
                <option value="nuit">Nuit</option>
                <option value="soir">Soir</option>
            </x-select.native>

            <x-input type="text" label="Salle" wire:model="salle" placeholder="101, 102, etc" id="salle" />

            <x-select.native wire:model="professeur_id" label="Professeur" id="professeur_id">
                <option value="">Sélectionner un professeur</option>
                @foreach (User::where('role', 'professeur')->get() as $professeur)
                    <option value="{{ $professeur->id }}">{{ $professeur->name }}</option>
                @endforeach
            </x-select.native>

            <x-select.native wire:model="jour" label="Jour" id="jour">
                <option value="">Sélectionner un jour</option>
                <option value="lundi">Lundi</option>
                <option value="mardi">Mardi</option>
                <option value="mercredi">Mercredi</option>
                <option value="jeudi">Jeudi</option>
                <option value="vendredi">Vendredi</option>
                <option value="samedi">Samedi</option>
                <option value="dimanche">Dimanche</option>

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
