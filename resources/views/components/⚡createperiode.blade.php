<?php
use App\Models\AnneeScolaire;
use Livewire\Component;
use TallStackUi\Facades\Toast;
use TallStackUi\Traits\Interactions;
new class extends Component
{
    use Interactions;
    public $nom;
    public $selectedannee;
    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }
    public function store()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
        ]);
        \App\Models\Periode::create([
            'nom' => $this->nom,
        ]);
        $this->reset();
        $this->toast()->success('Création réussie', 'La période a été créée avec succès.')->send();
    }
};
?>
<div class="w-200">
    <h1 class="font-medium text-[18px] my-3">Ajouter une période</h1>
    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 w-full gap-4">
            <x-input type="text" label="Nom de la période" wire:model="nom" placeholder="Ex: Semestre 1, Semestre 2, etc" id="periode" />
        </div>
        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la période
            </x-button>
        </div>
    </form>
</div>
