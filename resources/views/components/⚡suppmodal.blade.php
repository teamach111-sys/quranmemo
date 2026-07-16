<?php

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

new class extends Component {
    public int|array|null $itemid = null;
    public ?string $itemclass = null;

    #[\Livewire\Attributes\On('pickid')]
    public function setItem($class, $id)
    {
        $this->itemclass = $class;
        $this->itemid = $id;
    }

    public function destroy()
    {
        if ($this->itemid && $this->itemclass) {
            if (is_array($this->itemid)) {
                app($this->itemclass)->whereIn('id', $this->itemid)->delete();
                $this->dispatch('refreshtable');
                $this->dispatch('refreshniveaux');
                $this->dispatch('refreshetudiants');
                $this->dispatch('refresh-table2');
                $this->dispatch('refreshfiliere');
                $this->dispatch('refreshmatiere');
                $this->itemid = null;
            } else {
                app($this->itemclass)->find($this->itemid)->delete();
                $this->dispatch('refreshtable');
                $this->dispatch('refreshniveaux');
                $this->dispatch('refreshetudiants');
                $this->dispatch('refresh-table2');
                $this->dispatch('refreshfiliere');
                $this->dispatch('refreshmatiere');

                $this->itemid = null;
            }
        }
    }
};
?>

<div class="whitespace-normal w-100">

    <button type="button" id="closeModal" aria-label="Close modal"
        class="flex items-center absolute top-6 right-6 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
        <svg x-on:click="$tsui.close.modal('deletedata')" xmlns="http://www.w3.org/2000/svg"
            class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600 dark:fill-slate-400 dark:hover:fill-red-500"
            aria-hidden="true" viewBox="0 0 329.269 329">
            <path
                d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
        </svg>
    </button>

    <div class="text-center">

        <h3 id="modal-title" class="text-slate-900 text-base font-semibold dark:text-slate-50">
            Êtes-vous sûr de vouloir le supprimer ?</h3>
        <p class="text-slate-600 text-sm mt-2 leading-relaxed dark:text-slate-400  ">
            Cette action est permanente et ne peut être annulée. Une fois supprimé, l'élément sera retiré du tableau.
        </p>
    </div>

    <div class="flex justify-between gap-4 mt-6">
        <x-button type="button" x-on:click="$wire.destroy(); $tsui.close.modal('deletedata')"
            class="px-3.5 py-2 w-full dark:focus:!ring-darkdeletebutton dark:!text-darkcontenttext text-sm font-semibold rounded-md cursor-pointer dark:!bg-darkdeletebutton border border-red-600 transition-colors dark:hover:!bg-darkdeletebuttonhover focus:outline-none focus-visible:ring-2 focus-visible:ring-darkdeletebuttonhover">
            Oui, supprimer</x-button>
        <x-button type="button" id="cancelBtn" x-on:click="$tsui.close.modal('deletedata')"
            class="px-3.5 py-2 w-full text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-whitecontenttext   dark:border-neutral-600">
            Non, annuler</x-button>

    </div>
</div>
