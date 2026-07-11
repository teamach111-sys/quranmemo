<?php

use Livewire\Component;

new class extends Component {
   
};
?>

<x-slot:title>
    {{ __('Configuration') }}
</x-slot:title>

<div>
    <div class="flex flex-col gap-3">

        <div class="flex  justify-between gap-4 items-center">

            <h1 class="font-bold text-[20px] ">Configuration</h1>


            <div class="flex gap-2 ">

               
            </div>
        </div>
        
    </div>

    <div class="mt-5">
        <livewire:createannee />
    </div>
      <div class="mt-5">
       <livewire:supprimerannee />
    </div>

    <div class="mt-5">
        <livewire:donnerrole />
    </div>
</div>

