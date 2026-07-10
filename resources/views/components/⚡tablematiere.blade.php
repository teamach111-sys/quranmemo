<?php

use Livewire\Component;

new class extends Component {
    public $recherche = '';
    public $niveauid;
    public $niveau;

    public function mount(\App\Models\Niveau $niveau)
    {
        $this->niveauid = $niveau->id;
        $this->niveau = $niveau;
    }

    #[\Livewire\Attributes\On('refresh-table2')]
    public function render()
    {
        return view('⚡tablematiere', [
            'matieres' => \App\Models\Matiere::where('niveau_id', $this->niveauid)
                ->where('nom', 'LIKE', '%' . $this->recherche . '%')
                ->paginate(12),
        ]);
    }

    public function destroy(\App\Models\Matiere $matiere)
    {
        $matiere->delete();
    }
};
?>

<x-slot:title>
    {{ __('Ajouter une matière') }}
</x-slot:title>
<div>


    <h1 class="font-bold text-[20px] mb-3 ">{{ $niveau->nom }}
    </h1>




    <!-- Table -->
    <div class="min-w-full">
        <div
            class="border border-gray-200 dark:border-neutral-700 rounded-lg overflow-x-auto [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-thumb]:rounded-none [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <div class="py-3 px-4 border-b border-gray-200 dark:border-neutral-700 flex items-center justify-between">
                <!-- Header -->
                <div class="relative max-w-xs">
                    <label for="hs-table-search" class="sr-only">Recherche</label>
                    <input type="text" name="hs-table-search" id="hs-table-search"
                        class="py-1.5 sm:py-2 px-3 ps-9 block w-full h-10 bg-white dark:bg-neutral-800 border-gray-200 dark:border-neutral-700 shadow-2xs rounded-lg sm:text-sm text-gray-800 dark:text-neutral-200 placeholder:text-gray-500 dark:placeholder:text-neutral-400 focus:z-10 focus:border-gray-900 dark:focus:border-neutral-300 focus:ring-gray-900 dark:focus:ring-neutral-300 disabled:opacity-50 disabled:pointer-events-none"
                        placeholder="Rechercher des éléments" wire:model.live="recherche">
                    <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-3">
                        <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>


                </div>
                <div x-data="{ open: false }" class="flex gap-2">

                    <button
                        class="flex items-center gap-2 cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-phosphor-export
                            class="w-5 h-5" />Exporter</button>

                    <Button @click="open = true"
                        class="flex items-center gap-2 cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-phosphor-student
                            class="w-5 h-5" />
                        Ajouter une matière</Button>
                    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                        <div class="bg-white dark:bg-[#262626] rounded-lg p-6 w-full max-w-[800px]"
                            @click.outside="open = false; $wire.dispatch('reset-message')">
                            <livewire:creatematiere :niveau="$niveau" />
                        </div>
                    </div>

                </div>

            </div>
            <!-- End Header -->

            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="py-3 px-4 pe-0">
                            <div class="flex items-center h-5">
                                <input id="hs-table-search-checkbox-all" type="checkbox"
                                    class="shrink-0 size-4 bg-transparent border-gray-300 dark:border-neutral-600 rounded-sm shadow-2xs text-gray-800 dark:text-white focus:ring-0 focus:ring-offset-0 checked:bg-gray-800 dark:checked:bg-white checked:border-gray-800 dark:checked:border-white disabled:opacity-50 disabled:pointer-events-none">
                                <label for="hs-table-search-checkbox-all" class="sr-only">Case à cocher</label>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase">
                            Nom</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase">
                            Description</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase">
                            Année d'étude</th>

                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse ($matieres as $item)
                        <tr wire:key="niveau-{{ $item->id }}">
                            <td class="py-3 ps-4">
                                <div class="flex items-center h-5">
                                    <input type="checkbox"
                                        class="shrink-0 size-4 bg-transparent border-gray-300 dark:border-neutral-600 rounded-sm shadow-2xs text-gray-800 dark:text-white focus:ring-0 focus:ring-offset-0 checked:bg-gray-800 dark:checked:bg-white checked:border-gray-800 dark:checked:border-white disabled:opacity-50 disabled:pointer-events-none">
                                    <label class="sr-only">Case à cocher</label>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <span>{{ $item->nom }}</span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-800 dark:text-neutral-200">
                                {{ $item->description }}</td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-800 dark:text-neutral-200">
                                {{ $item->annee_etude }}</td>


                            <td
                                class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center gap-4 items-center">
                                <button type="button" wire:click="destroy({{ $item->id }})"
                                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 dark:text-white hover:text-gray-900 dark:hover:text-neutral-300 focus:outline-hidden focus:text-gray-900 dark:focus:text-neutral-300 disabled:opacity-50 disabled:pointer-events-none">Supprimer</button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-6 py-4 text-center text-sm text-gray-800 dark:text-neutral-200">
                                Aucun niveau trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
        <div class="mt-2">
            {{ $matieres->links() }}


        </div>
    </div>




</div>
