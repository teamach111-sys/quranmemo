<?php

use App\Models\Groupe;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lesgroupes = [];
    public $groupeselecte;

    public function mount()
    {
        $this->chargerGroupes();
    }

    #[\Livewire\Attributes\On('actualiser-groupes')]
    public function chargerGroupes()
    {
        $this->lesgroupes = Groupe::with('anneescolaire')->orderBy('nom')->get();
    }

    public function supprimer()
    {
        if (! $this->groupeselecte) {
            $this->toast()->error('Erreur', 'Veuillez sélectionner un groupe')->send();

            return;
        }

        Groupe::destroy($this->groupeselecte);
        $this->groupeselecte = null;
        $this->chargerGroupes();
        $this->toast()->success('Suppression réussie', 'Le groupe a été supprimé avec succès.')->send();
    }
};
?>

<div>
    <h1 class="font-medium text-[18px] my-4">Supprimer un groupe</h1>

    <div class="flex items-center gap-2 w-200">
        <div class="flex-1">
            <x-select.native wire:model="groupeselecte" name="groupe_id" id="groupe_delete_select">
                <option value="">Sélectionner un groupe</option>
                @foreach ($lesgroupes as $groupe)
                    <option value="{{ $groupe->id }}">
                        {{ $groupe->nom }}@if ($groupe->anneescolaire) ({{ $groupe->anneescolaire->libelle }})@endif
                    </option>
                @endforeach
            </x-select.native>
        </div>

        <x-button wire:click="supprimer" title="Supprimer le groupe"
            class="shrink-0 rounded-md bg-darkdeletebutton hover:bg-darkdeletebuttonhover text-white p-2 cursor-pointer transition-colors"><x-uiw-delete
                class="w-5 h-5" /></x-button>
    </div>
</div>