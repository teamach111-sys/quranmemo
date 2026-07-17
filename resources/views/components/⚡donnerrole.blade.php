<?php

use Livewire\Component;
use App\Models\User;
use TallStackUi\Traits\Interactions;
new class extends Component {
    use Interactions;
    public $utilisateurliste = [];
    public $roles = ['administrateur', 'secretaire', 'professeur'];
    public $utilisateurSelecte;
    public $roleSelecte;

    public function mount()
    {
        $this->utilisateurliste = User::all();
    }
    public function assignerRole()
    {
        if ($this->utilisateurSelecte && $this->roleSelecte) {
            $utilisateur = User::find($this->utilisateurSelecte);
            $utilisateur->role = $this->roleSelecte;
            $utilisateur->save();
            $this->toast()->success('Attribution réussie', 'Le rôle a été attribué avec succès.')->send();
        }
    }
   
};
?>

<div>
    <h1 class="font-medium text-[18px] my-3">Assigner un rôle à un utilisateur</h1>
   
    <div class="flex items-center gap-2 w-200">
        <div class="flex-1">
            <x-select.native wire:model="utilisateurSelecte" name="utilisateur_id" id="utilisateur_select">
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($utilisateurliste as $utilisateur)
                    <option value="{{ $utilisateur->id }}">
                        {{ $utilisateur->name }} - ({{ $utilisateur->role }})
                    </option>
                @endforeach
            </x-select.native>
        </div>

        <div class="flex-1">
            <x-select.native wire:model="roleSelecte" name="role_id" id="role_select">
                <option value="">Sélectionner un rôle</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}">
                        {{ $role }}
                    </option>
                @endforeach
            </x-select.native>
        </div>
    </div>
    <x-button wire:click="assignerRole"
        class="mt-4"><x-uiw-check
            class="w-5 h-5" />Assigner le Rôle</x-button>

</div>
