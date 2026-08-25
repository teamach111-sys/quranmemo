<?php
use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;
    public $periode;
    public $matiere;
    public $selectedpromo;
    public $selectedgroup;
    public $selectdate;
    public $selectedmatiere;
    public $selectedannee;
    public $selectedperiode;
    public $selectedpromotion;
    public $selectedgroupe;
    public array $notes = [];
    public array $observations = [];

    public function mount()
    {
        $this->selectedannee = session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id');
    }

    #[On('anneeChanged')]
    public function onChangeAnnee($id)
    {
        $this->selectedannee = $id;
        $this->selectpromo = null;
        $this->selectedmatiere = null;
        $this->selectedgroupe = null;
        $this->selectedmatiere = null;
        $this->selectedperiode = null;

    }

    /*
        Old bad code:
        There were NO updatedSelected* lifecycle hooks and NO loadNotes() method at all.
        When the user changed the promotion, periode, matiere, or groupe dropdowns,
        nothing happened — the $notes and $observations arrays stayed empty (or stale
        from a previous save() call in the same request). Previously saved notes in the
        database were completely ignored and never shown back to the user.

        // (no updatedSelectedpromotion, updatedSelectedperiode, etc.)
        // (no loadNotes method)

        Why it was wrong:
        1. After saving notes and refreshing the page (or changing a dropdown), all note
           inputs appeared blank even though data existed in the `notes` table.
        2. The user had no way to know if notes were already entered for a given
           promotion + periode + matiere + groupe combination.
        3. Stale data could persist in the $notes array if the user switched dropdowns
           without a full page reload, because nothing was resetting or reloading them.

        Fix:
        1. Added updatedSelected* hooks so that whenever any dropdown changes,
           loadNotes() is called automatically.
        2. updatedSelectedpromotion() also resets matiere & groupe since they depend on promotion.
        3. updatedSelectedperiode() also resets matiere since matiere depends on periode.
        4. Added loadNotes() which:
           - Resets $notes and $observations to [] (clears stale data).
           - Checks if the 3 required fields (promotion, periode, matiere) are selected.
           - Queries the notes table matching promotion_id + matiere_id + periode_id.
           - If a groupe is selected, narrows down to only students in that group.
           - Pre-fills $notes and $observations arrays keyed by etudiant_id so the
             inputs show the saved values immediately.
    */
    public function updatedSelectedpromotion()
    {
        $this->selectedmatiere = null;
        $this->selectedgroupe = null;
        $this->loadNotes();
    }

    public function updatedSelectedperiode()
    {
        $this->selectedmatiere = null;
        $this->loadNotes();
    }

    public function updatedSelectedmatiere()
    {
        $this->loadNotes();
    }

    public function updatedSelectedgroupe()
    {
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = [];
        $this->observations = [];

        if (!$this->selectedpromotion || !$this->selectedperiode || !$this->selectedmatiere) {
            return;
        }

        $query = Note::where('promotion_id', $this->selectedpromotion)
            ->where('matiere_id', $this->selectedmatiere)
            ->where('periode_id', $this->selectedperiode);

        if ($this->selectedgroupe) {
            $studentIds = Etudiant::where('promotion_id', $this->selectedpromotion)
                ->where('groupe_id', $this->selectedgroupe)
                ->pluck('id');
            $query->whereIn('etudiant_id', $studentIds);
        }

        $existingNotes = $query->get();

        foreach ($existingNotes as $note) {
            $this->notes[$note->etudiant_id] = $note->note;
            $this->observations[$note->etudiant_id] = $note->observation;
        }
        logger()->info($this->notes);
    }

    public function with(): array
    {
        $query = Etudiant::where('promotion_id', $this->selectedpromotion);
        if ($this->selectedgroupe) {
            $query->where('groupe_id', $this->selectedgroupe);
        }

        return [
            'headers' => [['index' => 'id', 'label' => '#'], ['index' => 'nom', 'label' => 'Nom'], ['index' => 'prenom', 'label' => 'Prénom'], ['index' => 'note', 'label' => 'Note'], ['index' => 'observation', 'label' => 'Observation']],
            'rows' => $query->forCurrentAnnee()->get(),
        ];
    }

    /*
        New Functionality: save()
        
        Why it was missing:
        Previously, there was no way to actually save the notes from the table. The UI inputs existed, 
        but no backend logic handled the inserts/updates to the Note model.
        
        Fix: Implemented save() method to loop through the livewire state ($this->notes) and persist them 
        using Note::updateOrCreate(), which safely handles both creating new notes and updating existing ones.
    */
    public function save()
    {
        if (!$this->selectedmatiere || !$this->selectedperiode || !$this->selectedpromotion) {
            return;
        }

        foreach ($this->notes as $etudiant_id => $noteValue) {
            if ($noteValue !== '' && $noteValue !== null) {
                Note::updateOrCreate(
                    [
                        'etudiant_id' => $etudiant_id,
                        'matiere_id' => $this->selectedmatiere,
                        'periode_id' => $this->selectedperiode,
                        'promotion_id' => $this->selectedpromotion,
                    ],
                    [
                        'note' => $noteValue,
                        'observation' => $this->observations[$etudiant_id] ?? null,
                    ]
                );
            }
        }
                $this->toast()->success('Notes enregistrées avec succès.')->send();

    }

    public function render()
    {
        $selectedpromotion = $this->selectedpromotion;
        $selectedperiode = $this->selectedperiode;

        return view('⚡note', [
            'periodes' => $selectedperiode,
            'promotions' => $selectedpromotion,
        ]);
    }
};
?>
<x-slot:title>
    {{ __('Notes d\'étudiants') }}
</x-slot:title>
<div>
    <div class="flex flex-col gap-3">
        <div class="flex justify-between gap-4 items-center">
            <h1 class="font-bold text-[20px]">Notes d'étudiants</h1>
          
        </div>
    </div>
    <div class="mt-5">
        <div class="flex flex-wrap  items-center gap-4 mb-4">
            <x-select.native wire:model.live="selectedpromotion" id="selectedpromotion">
                <option value="">Sélectionner une promotion</option>
                @foreach (\App\Models\Promotion::with('programme')->forCurrentAnnee()->get() as $m)
                    <option value="{{ $m->id }}">{{ $m->programme->nom }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedperiode" id="selectedperiode">
                <option value="">Sélectionner une periode</option>
                @foreach (\App\Models\Periode::with('matiere')->get() as $m)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedmatiere" id="selectedmatiere">
                <option value="">Sélectionner une matiere</option>
                {{-- 
                    Old bad code: 
                    @foreach (\App\Models\Classe::...->get() as $m)
                        <option value="{{ $m->id }}">{{ $m->matiere?->nom ?? 'no matiere' }}</option>
                    @endforeach

                    Why it was wrong: 
                    1. It iterated over all 'Classe' instances. Since a 'matiere' is taught to multiple groups, it would appear multiple times (duplicates).
                    2. The option value was set to the specific class ID ($m->id) instead of the subject ID ($m->matiere_id) which is what you actually need to filter notes.
                    
                    Fix: Added ->unique('matiere_id') to remove duplicates and changed option value to {{ $m->matiere_id }}
                --}}
                @foreach (\App\Models\Classe::with('matiere', 'matiere.periode')->where('promotion_id', $selectedpromotion)->whereHas('matiere', function ($query) use ($selectedperiode) {
            $query->where('periode_id', $selectedperiode);
        })->get()->unique('matiere_id') as $m)
                    <option value="{{ $m->matiere_id }}">{{ $m->matiere?->nom ?? 'no matiere' }}</option>
                @endforeach
            </x-select.native>
            <x-select.native wire:model.live="selectedgroupe" id="selectedgroupe">
                <option value="">Sélectionner un groupe</option>
                {{-- 
                    Old bad code: 
                    @foreach (\App\Models\Classe::with('groupe')->where('promotion_id', $selectedpromotion)->get() as $m)
                        <option value="{{ $m->id }}">{{ $m->groupe->nom }}</option>
                    @endforeach

                    Why it was wrong:
                    1. Similar to matiere, iterating over 'Classe' without grouping caused the same group to appear multiple times (once for each subject in that group).
                    2. The option value was set to the class ID ($m->id) instead of the actual group ID ($m->groupe_id).

                    Fix: Added ->unique('groupe_id') to remove duplicates and changed option value to {{ $m->groupe_id }}
                --}}
                @foreach (\App\Models\Classe::with('groupe')->where('promotion_id', $selectedpromotion)->get()->unique('groupe_id') as $m)
                    <option value="{{ $m->groupe_id }}">{{ $m->groupe->nom }}</option>
                @endforeach
            </x-select.native>
        </div>
        <x-table :$headers :$rows >
            @interact('column_note', $row)
                    <x-input type="number" min="0" max="20" oninput="if (this.value > 20) this.value = 20;" wire:model="notes.{{ $row->id }}" />
            @endinteract
               @interact('column_observation', $row)
                    <x-input type="text" wire:model="observations.{{ $row->id }}" />
                
            @endinteract
        </x-table>
        <div class="mt-3 flex justify-end items-center gap-3">
            <x-button text="Enregistrer" wire:click="save" />
            <x-button text="Imprimer" />
        </div>
    </div>
</div>
