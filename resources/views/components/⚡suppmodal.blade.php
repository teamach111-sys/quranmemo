<?php

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;


new class extends Component {
    public Model $item;
    public $itemid;
    public function mount(Model $item)
    {
        $this->item = $item;
        $this->itemid = $item->id;
    }

    public function destroy()
    {
        $this->item->delete();
        $this->dispatch('refreshtable');
        $this->dispatch('refreshniveaux');
        $this->dispatch('refreshparent');
        
    }
};
?>

    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1" @click.outside="open = false"
        class="w-full max-w-lg mx-auto whitespace-normal bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6 dark:bg-neutral-800 dark:border-neutral-700">

        <button type="button" id="closeModal" aria-label="Close modal"
            class="flex items-center absolute top-6 right-6 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
            <svg @click="open = false" xmlns="http://www.w3.org/2000/svg"
                class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600 dark:fill-slate-400 dark:hover:fill-red-500"
                aria-hidden="true" viewBox="0 0 329.269 329">
                <path
                    d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
            </svg>
        </button>

        <div class="text-center">
            <div class="w-14 h-14 p-3.5 mb-4 mx-auto rounded-full bg-red-50 dark:bg-red-300/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-full fill-red-500 inline" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        d="M19 7a1 1 0 0 0-1 1v11.191A1.92 1.92 0 0 1 15.99 21H8.01A1.92 1.92 0 0 1 6 19.191V8a1 1 0 0 0-2 0v11.191A3.918 3.918 0 0 0 8.01 23h7.98A3.918 3.918 0 0 0 20 19.191V8a1 1 0 0 0-1-1Zm1-3h-4V2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H4a1 1 0 0 0 0 2h16a1 1 0 0 0 0-2ZM10 4V3h4v1Z"
                        data-original="#000000" />
                    <path d="M11 17v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Zm4 0v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Z"
                        data-original="#000000" />
                </svg>
            </div>
            <h3 id="modal-title" class="text-slate-900 text-base font-semibold dark:text-slate-50">
                Êtes-vous sûr de vouloir le supprimer ?</h3>
            <p class="text-slate-600 text-sm mt-2 leading-relaxed dark:text-slate-400  ">
                Cette action est permanente et ne peut être annulée. Une fois supprimé, l'élément sera retiré du tableau.</p>
        </div>

        <div class="flex justify-between gap-4 mt-6">
            <button type="button" id="cancelBtn" @click="open = false"
                class="px-3.5 py-2 w-full text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:border-neutral-600">
                Non, annuler</button>
            <button type="button" @click="open = false; $wire.destroy()"
                class="px-3.5 py-2 w-full text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                Oui, supprimer</button>
        </div>
    </div>
