<?php

use Livewire\Component;

new class extends Component
{
    public $niveauid;
    public $niveau;

    public function mount(\App\Models\Niveau $niveau)
    {
        $this->niveauid = $niveau->id;
        $this->niveau = $niveau;
    }
};
?>

<div >
    <livewire:tablematiere2 :niveau="$niveauid" />


</div>