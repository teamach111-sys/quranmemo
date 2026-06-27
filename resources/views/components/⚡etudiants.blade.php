<?php

use Livewire\Component;
use App\Models\Etudiant;
use Livewire\Attributes\Title;

new class extends Component {
 
    
};
?>

<x-slot:title>
    {{ __('Gestion des etudiants') }}
</x-slot:title>

<div>
    <div class="flex  justify-between gap-4 items-center">

        <h1 class="font-bold text-[20px] ">Gestion des etudiants</h1>


        <div class="flex gap-2 ">
           
            <div x-data="{ open: false }">
                <Button @click="open = true"
                    class="cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm p-2 justify-center items-center ">Nouveau
                    etudiant</Button>
                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                    <div class="bg-white dark:bg-[#262626] rounded-lg p-6 w-full max-w-[800px]"
                        @click.outside="open=false">
                        <livewire:createetudiant />
                    </div>
                </div>
            </div>



        </div>






    </div>
    <div class="mt-5">
        <livewire:etudianttable/>
    </div>
</div>
