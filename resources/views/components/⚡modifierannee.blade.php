<?php

use App\Models\AnneeScolaire;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $lesannees = [];
    public $anneeselecte;
    public $date_debut;
    public $date_fin;
    public $est_en_cours = false;

    public function mount()
    {
        $this->chargerAnnees();
    }

    #[\Livewire\Attributes\On('reloadAnnees')]
    #[\Livewire\Attributes\On('actualiser-annee')]
    public function chargerAnnees()
    {
        $this->lesannees = AnneeScolaire::orderBy('id', 'desc')->get();
    }

    public function chargerAnnee()
    {
        $annee = AnneeScolaire::find($this->anneeselecte);

        if (! $annee) {
            $this->reset(['date_debut', 'date_fin', 'est_en_cours']);

            return;
        }

        $this->date_debut = $annee->date_debut;
        $this->date_fin = $annee->date_fin;
        $this->est_en_cours = (bool) $annee->est_en_cours;
    }

    public function modifier()
    {
        $this->validate([
            'anneeselecte' => 'required',
            'date_debut' => 'required|date|before:date_fin',
            'date_fin' => 'required|date|after:date_debut',
            'est_en_cours' => 'nullable|boolean',
        ]);

        $annee = AnneeScolaire::findOrFail($this->anneeselecte);

        if ($this->est_en_cours) {
            AnneeScolaire::where('id', '!=', $annee->id)->update(['est_en_cours' => false]);
        }

        $annee->update([
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'est_en_cours' => $this->est_en_cours,
        ]);

        $this->reset(['anneeselecte', 'date_debut', 'date_fin', 'est_en_cours']);
        $this->chargerAnnees();
        $this->dispatch('reloadAnnees');
        $this->dispatch('actualiser-annee');
        $this->toast()->success('Modification réussie', 'L\'année scolaire a été modifiée avec succès.')->send();
    }
};
?>

<div class="w-200">
    <h1 class="font-medium text-[18px] my-3">Modifier une année scolaire</h1>

    <form wire:submit.prevent="modifier">
        <div class="grid grid-cols-2 w-full gap-4">

            <div class="col-span-2">
                <x-select.native wire:model="anneeselecte" wire:change="chargerAnnee" name="annee_modif_id" id="annee_modif_select">
                    <option value="">Sélectionner une année</option>
                    @foreach ($lesannees as $annee)
                        <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                    @endforeach
                </x-select.native>
            </div>

            <div>
                <x-date format="YYYY-MM-DD" label="Date début" wire:model="date_debut" id="modif_date_debut" />
            </div>

            <div>
                <x-date format="YYYY-MM-DD" label="Date fin" wire:model="date_fin" id="modif_date_fin" />
            </div>

            <div class="flex items-center gap-2 mt-6">
                <x-checkbox label="Année courante" wire:model="est_en_cours" id="modif_est_en_cours" />
            </div>
        </div>

        <x-button type="submit"
            class="mt-4 dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
            Modifier l'Année
        </x-button>
    </form>
</div>