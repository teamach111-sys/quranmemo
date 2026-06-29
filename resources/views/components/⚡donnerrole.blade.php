<?php

use Livewire\Component;
use App\Models\User;
new class extends Component {
    public $utilisateurliste = [];
    public $roles = ['administrateur', 'secretaire', 'professeur'];
    public $message = null;
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
            $this->message = 'Rôle assigné avec succès';
        }
    }
    public function resetMessage()
    {
        $this->message = null;
    }
};
?>

<div>
    <h1 class="font-medium text-[18px] my-3">Assigner un rôle à un utilisateur</h1>
    <div class="w-200 my-3">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong>Erreur!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            @if ($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" x-data
                    x-init="setTimeout(() => $wire.resetMessage(), 3000)">
                    <strong>Succès!</strong>
                    <p>{{ $message }}</p>
                </div>
            @endif
        @endif


    </div>
    <div class="flex  items-center gap-2 w-200">
        <select wire:model="utilisateurSelecte" name="utilisateur_id" id="utilisateur_select"
            class="p-2 border focus:outline-none border-[#E5E5E5] dark:border-[#3E3E3E] h-10 rounded-md w-full">
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">Sélectionner un
                utilisateur</option>
            @foreach ($utilisateurliste as $utilisateur)
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                    value="{{ $utilisateur->id }}">
                   {{ $utilisateur->name }}-<span class="text-gray-400 dark:text-gray-600">({{ $utilisateur->role }})</span>
            @endforeach
        </select>

        <select wire:model="roleSelecte" name="role_id" id="role_select"
            class="p-2 border focus:outline-none border-[#E5E5E5] dark:border-[#3E3E3E] h-10 rounded-md w-full">
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">Sélectionner un
                rôle</option>
            @foreach ($roles as $role)
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border"
                    value="{{ $role }}">
                    {{ $role }}
                </option>
            @endforeach
        </select>



    </div>
    <Button wire:click="assignerRole"
        class="my-5 flex items-center gap-2 cursor-pointer h-10 bg-[#262626] hover:bg-[#3B3B3B] dark:bg-white dark:text-black dark:hover:bg-slate-100 border text-white rounded-sm px-4 py-2 justify-center"><x-uiw-check
            class="w-5 h-5" />Assigner le Rôle</Button>

</div>
