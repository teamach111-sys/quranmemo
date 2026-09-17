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

    public function retirerRole()
    {
        if (! $this->utilisateurSelecte) {
            $this->toast()->error('Erreur', 'Veuillez sélectionner un utilisateur')->send();

            return;
        }

        $utilisateur = User::find($this->utilisateurSelecte);
        $utilisateur->role = null;
        $utilisateur->save();
        $this->utilisateurliste = User::all();
        $this->toast()->success('Rôle retiré', 'Le rôle a été retiré avec succès.')->send();
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
                        {{ $utilisateur->name }} - ({{ $utilisateur->role ?: 'aucun rôle' }})
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
    <div class="mt-4 flex items-center gap-2">
        <x-button wire:click="assignerRole"
            class="dark:!bg-darkaddbutton dark:text-black dark:focus:!ring-darkaddbuttonring
  flex-1 rounded-md bg-darkcontentbg hover:!bg-darkaddbuttonhover text-white px-4 py-2 cursor-pointer "><x-uiw-check
                class="w-5 h-5" />Assigner le Rôle</x-button>

        <x-button wire:click="retirerRole"
            class="dark:!bg-darkdeletebutton dark:text-white dark:focus:!ring-darkdeletebutton
  flex-1 rounded-md bg-darkdeletebutton hover:!bg-darkdeletebuttonhover text-white px-4 py-2 cursor-pointer "><x-uiw-delete
                class="w-5 h-5" />Retirer le Rôle</x-button>
    </div>

</div>
