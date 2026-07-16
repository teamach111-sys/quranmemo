<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public $annees = [];

    public function mount(){
        $this->jibannees();
    }

    #[On('annee-cree')]
    public function jibannees(){
        $this->annees = \App\Models\AnneeScolaire::orderBy('id','desc')->get();
    }
};
?>

<div class="w-50">
    
      <x-select.native name="" id=""
              >
              @foreach ($annees as $annee)
                  <option  value="{{ $annee->id }}">{{ $annee->libelle }}</option>
              @endforeach
          </x-select.native>
         
</div>