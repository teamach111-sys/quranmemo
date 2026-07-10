<?php

use Livewire\Component;

new class extends Component
{
    public $niveau_id;
    public $message;
    public $niveau;

    #[\Livewire\Attributes\On('mount')]
    public function mount(\App\Models\Niveau $niveau)
    {
        $this->niveau_id = $niveau->id;
        $this->niveau = $niveau;
    }
};
?>

<div>
    <livewire:tablematiere :niveau="$niveau" />

</div>