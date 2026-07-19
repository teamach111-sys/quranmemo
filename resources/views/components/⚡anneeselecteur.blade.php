<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends Component
{
    // Sync the select value directly with the browser URL bar (?annee=X)
    #[Url(as: 'annee')]
    public $globalFilter = '';
    
    public $annees = [];

    /**
     * Boot runs on every single request lifecycle.
     * Perfect for handling cross-page persistence and URL sync states.
     */
    public function boot()
    {
        // 1. If URL has a value, force sync it into the session storage
        if (!empty($this->globalFilter)) {
            session(['active_annee_id' => $this->globalFilter]);
        } 
        // 2. If URL is empty, read from session or fallback to default
        else {
            $this->globalFilter = session('active_annee_id', function() {
                $this->jibannees();
                $active = \App\Models\AnneeScolaire::where('est_en_cours', true)->first() 
                    ?? $this->annees->first();
                    
                if ($active) {
                    session(['active_annee_id' => $active->id]);
                    return $active->id;
                }
                return '';
            });
        }
    }

    public function mount()
    {
        $this->jibannees();
    }

    public function updatedGlobalFilter($value)
    {
        // Save the value to the session the absolute millisecond it changes
        session(['active_annee_id' => $value]);

        // Dispatch an event for components sharing the exact same page layout
        $this->dispatch('global-annee-changed', filterId: $value);
    }

    #[On('annee-cree')]
    public function jibannees()
    {
        $this->annees = \App\Models\AnneeScolaire::orderBy('id', 'desc')->get();
    }
};
?>

<div class="w-50">
    <x-select.native wire:model.live="globalFilter" id="global_annee_select">
        <option value="">Sélectionnez une année...</option>
        @foreach ($annees as $annee)
            <option value="{{ $annee->id }}" wire:key="global-annee-{{ $annee->id }}">
                {{ $annee->libelle }} @if($annee->est_en_cours) (Actuelle) @endif
            </option>
        @endforeach
    </x-select.native>
</div>