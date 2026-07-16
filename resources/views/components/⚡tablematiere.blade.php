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
    <div class="min-w-full mt-5">
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
                        class="flex items-center gap-2 cursor-pointer h-10 bg-darkcontentbg hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-phosphor-export
                            class="w-5 h-5" />Exporter</button>

                    <Button @click="open = true"
                        class="flex items-center gap-2 cursor-pointer h-10 bg-darkcontentbg hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-codicon-add
                            class="h-5 w-5" />
                        Ajouter une matière</Button>
                    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                        <div class="bg-white dark:bg-darkcontentbg rounded-lg p-6 w-full max-w-[800px]"
                            @click.outside="open = false; $wire.dispatch('reset-message')">
                            <livewire:creatematiere :niveau="$niveau" />
                        </div>
                    </div>

                </div>

            </div>
            <!-- End Header -->

            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                        @for ($i = 1; $i <= $niveau->nombre_annees; $i++)
                            <th scope="col"
                                class="px-6 py-3 text-left  text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $i }} {{ $i == 1 ? 'ère année' : 'ème année' }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                        @for ($i = 1; $i <= $niveau->nombre_annees; $i++)
                            <td
                                class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-800 dark:text-neutral-200 align-top">
                                @forelse ($matieres->groupBy('annee_etude')->get($i, collect()) as $matiere)
                                    <div class="flex justify-between items-center">
                                        <div wire:key="{{ $matiere->id }}" class="py-2    ">{{ $matiere->nom }}</div>

                                        <div x-data="{ open: false }">
                                            <x-uiw-delete @click="open=true" class="w-5 h-5 cursor-pointer" />
                                            <div x-show="open" x-cloak id="modalOverlay"
                                                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)]">
                                                <livewire:suppmodal :item="$matiere" />
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <span
                                        class="px-6 py-3 text-center  text-sm font-medium text-gray-800 dark:text-neutral-200">Aucune
                                        matière trouvée
                                    </span>
                                @endforelse
                            </td>
                        @endfor
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="mt-2">
            {{ $matieres->links() }}


        </div>
    </div>




</div>
