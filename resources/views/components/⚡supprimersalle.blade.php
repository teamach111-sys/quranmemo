<?php

use App\Models\Salle;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lessalles = [];
    public $salleselecte;

    public function mount()
    {
        $this->chargerSalles();
    }

    #[\Livewire\Attributes\On('actualiser-salles')]
    public function chargerSalles()
    {
        $this->lessalles = Salle::orderBy('nom')->get();
    }

    public function supprimer()
    {
        if (! $this->salleselecte) {
            $this->toast()->error('Erreur', 'Veuillez sélectionner une salle')->send();

            return;
        }

        Salle::destroy($this->salleselecte);
        $this->salleselecte = null;
        $this->chargerSalles();
        $this->toast()->success('Suppression réussie', 'La salle a été supprimée avec succès.')->send();
    }
};
?>

<div>
    <h1 class="font-medium text-[18px] my-4">Supprimer une salle</h1>

    <div class="flex items-center gap-2 w-200">
        <div class="flex-1">
            <x-select.native wire:model="salleselecte" name="salle_id" id="salle_delete_select">
                <option value="">Sélectionner une salle</option>
                @foreach ($lessalles as $salle)
                    <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                @endforeach
            </x-select.native>
        </div>

        <x-button wire:click="supprimer"
            class="dark:!bg-darkdeletebutton dark:text-white dark:focus:!ring-darkdeletebutton
  flex-1 rounded-md bg-darkdeletebutton hover:!bg-darkdeletebuttonhover text-white px-4 py-2 cursor-pointer ">
            <x-uiw-delete class="w-5 h-5" />Supprimer la salle
        </x-button>
    </div>
</div>