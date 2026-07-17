<?php

use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lesannees = [];
    public $anneeselecte;
    #[\Livewire\Attributes\On('actualiser-annee')]
    public function jibannees1()
    {
        $this->lesannees = \App\Models\AnneeScolaire::orderBy('id', 'desc')->get();
    }
    public function supprimerlannee()
    {
        if ($this->anneeselecte) {
            \App\Models\AnneeScolaire::destroy($this->anneeselecte);
            $this->anneeselecte = null;
            $this->jibannees1();
            $this->dispatch('annee-cree');
            $this->toast()->success('Suppression réussie', 'L\'année scolaire a été supprimée avec succès.')->send();


        }else{
            $this->toast()->error('Erreur', 'Veuillez sélectionner une année scolaire')->send();

        }
    }
    public function resetMessage()
    {
        $this->resetValidation();
    }
    public function mount()
    {
        $this->jibannees1();
    }
};
?>

<div>
    <h1 class="font-medium text-[18px] my-4">Supprimer une année scolaire</h1>
    
    <div class="flex items-center gap-2 w-200">
        <div class="flex-1">
            <x-select.native wire:model="anneeselecte" name="annee_id" id="annee_select">
                <option value="">Sélectionner une année</option>
                @foreach ($lesannees as $annee)
                    <option value="{{ $annee->id }}">
                        {{ $annee->libelle }}
                    </option>
                @endforeach
            </x-select.native>
        </div>
        <x-button wire:click="supprimerlannee"
            class=""><x-uiw-delete
                class="w-5 h-5" />Supprimer
            l'Année</x-button>


    </div>
</div>
