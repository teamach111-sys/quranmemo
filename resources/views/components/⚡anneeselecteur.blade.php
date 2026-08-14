<?php

use App\Models\AnneeScolaire;
use Livewire\Component;

new class extends Component
{
    public $annees;
    public $selectedannee;

    public function mount()
    {
        $this->annees = AnneeScolaire::all();
        
        $this->selectedannee = session('selected_annee_id', function () {
            return AnneeScolaire::where('est_en_cours', true)->value('id');
        });

        if ($this->selectedannee && !session()->has('selected_annee_id')) {
            session(['selected_annee_id' => $this->selectedannee]);
        }
    }

    public function updatedSelectedannee($value)
    {
        session(['selected_annee_id' => $value]);
        
        $this->dispatch('anneeChanged', id: $value);
    }
};
?>

<div class="w-50">
    <x-select.native wire:model.live="selectedannee" id="selectedannee">
        <option value="">Sélectionnez une année...</option>
        @foreach ($annees as $annee)
            <option value="{{ $annee->id }}" wire:key="global-annee-{{ $annee->id }}">
                {{ $annee->libelle }} @if($annee->est_en_cours) (Actuelle) @endif
            </option>
        @endforeach
    </x-select.native>
</div>