<?php

use App\Models\Salle;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lessalles = [];
    public $salleselecte;
    public $nom;
    public $capacite;

    public function mount()
    {
        $this->chargerSalles();
    }

    #[\Livewire\Attributes\On('actualiser-salles')]
    public function chargerSalles()
    {
        $this->lessalles = Salle::orderBy('nom')->get();
    }

    public function chargerSalle()
    {
        $salle = Salle::find($this->salleselecte);

        if (! $salle) {
            $this->reset(['nom', 'capacite']);

            return;
        }

        $this->nom = $salle->nom;
        $this->capacite = $salle->capacite;
    }

    public function modifier()
    {
        $this->validate([
            'salleselecte' => 'required',
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
        ]);

        $salle = Salle::findOrFail($this->salleselecte);

        $salle->update([
            'nom' => $this->nom,
            'capacite' => $this->capacite,
        ]);

        $this->reset(['salleselecte', 'nom', 'capacite']);
        $this->chargerSalles();
        $this->dispatch('actualiser-salles');
        $this->toast()->success('Modification réussie', 'La salle a été modifiée avec succès.')->send();
    }
};
?>

<div class="w-200">
    <h1 class="font-medium text-[18px] my-3">Modifier une salle</h1>

    <form wire:submit.prevent="modifier">
        <div class="grid grid-cols-2 w-full gap-4">

            <div class="col-span-2">
                <x-select.native wire:model="salleselecte" wire:change="chargerSalle" name="salle_modif_id" id="salle_modif_select">
                    <option value="">Sélectionner une salle</option>
                    @foreach ($lessalles as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                    @endforeach
                </x-select.native>
            </div>

            <div>
                <x-input label="Nouveau nom" type="text" wire:model="nom" id="modif_nom_salle" />
            </div>

            <div>
                <x-input label="Nouvelle capacité" type="number" wire:model="capacite" id="modif_capacite" />
            </div>
        </div>

        <x-button type="submit"
            class="mt-4 dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
            Modifier la salle
        </x-button>
    </form>
</div>