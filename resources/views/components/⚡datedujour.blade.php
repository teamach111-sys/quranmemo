<?php

use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return view('⚡datedujour');
    }
};
?>

<div>
   <div wire:poll.60000 class="flex items-center gap-2">
          <p>Date du jour:</p>
          <div class="flex items-center gap-2">
              <p>{{ Str::ucfirst(now()->isoFormat('dddd DD-MM-YYYY HH:mm')) }}</p>
              <span class="text-gray-300 dark:text-gray-600">|</span>
              <p class="text-gray-500 dark:text-gray-400">
                  {{ Str::ucfirst(\IntlDateFormatter::create('fr_FR@calendar=islamic', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, \IntlDateFormatter::TRADITIONAL, 'd MMMM y G')->format(now()->timestamp)) }}
              </p>
          </div>
      </div>
</div>