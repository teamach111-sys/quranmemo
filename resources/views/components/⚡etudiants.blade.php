<?php

use Livewire\Component;
use App\Models\Etudiant;
use Livewire\Attributes\Title;

new class extends Component {};
?>

<x-slot:title>
    {{ __('Gestion des etudiants') }}
</x-slot:title>

<div>
    <div class="flex flex-col gap-3">

        <div class="flex  justify-between gap-4 items-center">

            <h1 class="font-bold text-[20px] ">Gestion des etudiants</h1>


            <div class="flex gap-2 ">

               
            </div>
        </div>
        
    </div>

    <div class="mt-5">
        <livewire:etudianttable />
    </div>
</div>
