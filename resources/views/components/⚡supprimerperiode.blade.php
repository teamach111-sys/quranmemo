<?php

use App\Models\Periode;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lesperiodes = [];
    public $periodeselecte;

    public function mount()
    {
        $this->chargerPeriodes();
    }

    #[\Livewire\Attributes\On('actualiser-periodes')]
    public function chargerPeriodes()
    {
        $this->lesperiodes = Periode::orderBy('nom')->get();
    }

    public function supprimer()
    {
        if (! $this->periodeselecte) {
            $this->toast()->error('Erreur', 'Veuillez sélectionner une période')->send();

            return;
        }

        Periode::destroy($this->periodeselecte);
        $this->periodeselecte = null;
        $this->chargerPeriodes();
        $this->toast()->success('Suppression réussie', 'La période a été supprimée avec succès.')->send();
    }
};
?>

<div>
    <h1 class="font-medium text-[18px] my-4">Supprimer une période</h1>

    <div class="flex items-center gap-2 w-200">
        <div class="flex-1">
            <x-select.native wire:model="periodeselecte" name="periode_id" id="periode_delete_select">
                <option value="">Sélectionner une période</option>
                @foreach ($lesperiodes as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->nom }}</option>
                @endforeach
            </x-select.native>
        </div>

        <x-button wire:click="supprimer"
            class="dark:!bg-darkdeletebutton dark:text-white dark:focus:!ring-darkdeletebutton
  flex-1 rounded-md bg-darkdeletebutton hover:!bg-darkdeletebuttonhover text-white px-4 py-2 cursor-pointer ">
            <x-uiw-delete class="w-5 h-5" />Supprimer la période
        </x-button>
    </div>
</div>