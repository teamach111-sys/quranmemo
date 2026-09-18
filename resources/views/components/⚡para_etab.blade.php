<?php
use Livewire\Component;

new class extends Component {};
?>
<x-slot:title>
    {{ __('Configuration') }}
</x-slot:title>
<div class="w-auto">
    <div class="flex flex-col gap-3">
        <div class="flex  justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px] ">Configuration</h1>
            <div class="flex gap-2 ">
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        <x-tab selected="Années Scolaires">
            <x-tab.items tab="Années Scolaires">
                <div class="border dark:border-darkborder rounded-md p-4 w-fit">
                    <livewire:createannee/>
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:modifierannee/>
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:supprimerannee/>
                </div>
            </x-tab.items>

            <x-tab.items tab="Rôles">
                <div class="border border-darkborder rounded-md p-4 w-fit">
                    <livewire:donnerrole />
                </div>
            </x-tab.items>

            <x-tab.items tab="Salles">
                <div class="border border-darkborder rounded-md p-4 w-fit">
                    <livewire:createsalle />
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:modifiersalle />
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:supprimersalle />
                </div>
            </x-tab.items>

            <x-tab.items tab="Groupes">
                <div class="border border-darkborder rounded-md p-4 w-fit">
                    <livewire:creategroupe />
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:supprimergroupe />
                </div>
            </x-tab.items>

            <x-tab.items tab="Périodes">
                <div class="border dark:border-darkborder rounded-md p-4 w-fit">
                    <livewire:createperiode />
                    <hr class="border-t dark:border-darkborder my-6" />
                    <livewire:supprimerperiode />
                </div>
            </x-tab.items>
        </x-tab>
    </div>
</div>