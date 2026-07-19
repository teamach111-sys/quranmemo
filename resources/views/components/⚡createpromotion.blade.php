<?php

use Livewire\Component;
use TallStackUi\Traits\Interactions;
use App\Models\Programme;
use App\Models\Niveau;
use App\Models\AnneeScolaire;
use App\Models\Promotion;

new class extends Component {
    use Interactions;

    public ?int $programme_id = null;
    public ?int $niveau_id = null;
    public ?int $annee_etude = null;
    public ?int $annee_scolaire_id = null;

    public function mount()
    {
        $this->annee_scolaire_id = AnneeScolaire::where('est_active', true)->orWhere('est_en_cours', 1)->first()?->id;
    }

    public function updatedProgrammeId()
    {
        $this->niveau_id = null;
        $this->annee_etude = null;
    }

    public function updatedNiveauId()
    {
        $this->annee_etude = null;
    }

    #[\Livewire\Attributes\Computed]
    public function filteredNiveaux()
    {
        return $this->programme_id ? Niveau::where('programme_id', $this->programme_id)->get() : collect();
    }

    #[\Livewire\Attributes\Computed]
    public function availableAnnees()
    {
        if (!$this->niveau_id) {
            return collect();
        }

        $niveau = Niveau::find($this->niveau_id);
        $nombreAnnees = $niveau->nombre_annees;

        return collect(range(1, $nombreAnnees));
    }

    public function store()
    {
        $this->validate([
            'programme_id' => 'required|integer|exists:programmes,id',
            'niveau_id' => 'required|integer|exists:niveaux,id',
            'annee_etude' => 'required|integer|min:1',
            'annee_scolaire_id' => 'required|integer|exists:annee_scolaires,id',
        ]);

        $exists = Promotion::where('programme_id', $this->programme_id)
            ->where('niveau_id', $this->niveau_id)
            ->where('annee_etude', $this->annee_etude)
            ->where('annee_scolaire_id', $this->annee_scolaire_id)
            ->exists();

        if ($exists) {
            $this->toast()->warning('Promotion existe déjà')->send();
            return;
        }

        Promotion::create([
            'programme_id' => $this->programme_id,
            'niveau_id' => $this->niveau_id,
            'annee_etude' => $this->annee_etude,
            'annee_scolaire_id' => $this->annee_scolaire_id,
        ]);

        $this->reset();
        $this->dispatch('refreshPromotion');

        $this->toast()->success('Promotion créée', 'La promotion a été créée avec succès.')->send();
    }
};
?>

<div class="w-200">

    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <x-select.native wire:model.live="programme_id" label="Filière" id="programme_id">
                <option value="">Sélectionner une filière</option>
                @foreach (Programme::all() as $programme)
                    <option value="{{ $programme->id }}">{{ $programme->nom }}</option>
                @endforeach
            </x-select.native>

            <x-select.native wire:model.live="niveau_id" label="Niveau" id="niveau_id">
                <option value="">Sélectionner un niveau</option>
                @foreach ($this->filteredNiveaux as $niveau)
                    <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                @endforeach
            </x-select.native>

            <x-select.native wire:model="annee_etude" label="Année d'Étude" id="annee_etude">
                <option value="">Sélectionner une année</option>
                @foreach ($this->availableAnnees as $annee)
                    <option value="{{ $annee }}">
                        {{ $annee }}{{ $annee == 1 ? 'ère' : 'ème' }} Année
                    </option>
                @endforeach
            </x-select.native>
        </div>

        <div class="flex gap-3 pt-4">
            <x-button type="submit"
                class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer ">
                Ajouter la promotion
            </x-button>

            <x-button type="button" x-on:click="$tsui.close.modal('createpromotion')"
                class=" dark:text-black 
  flex-1 rounded-md   text-white px-4 py-2 cursor-pointer ">
                Fermer
            </x-button>
        </div>
    </form>
</div>