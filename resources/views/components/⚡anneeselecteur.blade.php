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

<div>
    
      <select name="" id=""
              class="p-2 border focus:outline-none border-[#E5E5E5
] dark:border-[#3E3E3E] h-10 rounded-md w-40">
              @foreach ($annees as $annee)
                  <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="{{ $annee->id }}">{{ $annee->libelle }}</option>
              @endforeach
          </select>
</div>