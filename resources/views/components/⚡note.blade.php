<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
<x-modal id="test1" center >
    


</x-modal>

<x-button x-on:click="$tsui.open.modal('test1')">
    cliquer
</x-button>

</div>