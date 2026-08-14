<?php

use Livewire\Component;
use App\Models\Classe;
use App\Models\Promotion;
use App\Models\Salle;
use App\Models\User;
new class extends Component {
    public $anneedetude;
    public $groupe;
    public Promotion $promotion;
    public Classe $classe;
    public string $selectedgroupe;
    public $promotion_id;
    public $timeSlots = [];
    public $classes = [];

    public function mount($promotion, $groupe)
    {
        $this->promotion = $promotion;
        $this->promotion_id = $promotion->id;
        $this->selectedgroupe = $groupe;

        $this->load();
    }

    public function load()
    {
        $this->classes = Classe::with('matiere', 'professeur')
        ->where('promotion_id', $this->promotion_id)
        ->where('groupe', $this->selectedgroupe)
        ->orderBy('heure_debut','ASC')
        ->orderByRaw("CASE LOWER(jour) WHEN 'lundi' THEN 1 WHEN 'mardi' THEN 2 WHEN 'mercredi' THEN 3 WHEN 'jeudi' THEN 4 WHEN 'vendredi' THEN 5 WHEN 'samedi' THEN 6 WHEN 'dimanche' THEN 7 ELSE 8 END")->get();

        $this->timeSlots = $this->classes->map(fn($c) => $c->heure_debut . ' - ' . $c->heure_fin)->unique()->values()->sort()->toArray();
    }
};
?>

<div id="schedule-print-area" class="bg-white text-black">
    <div class="p-4 max-w-4xl mx-auto">
        <div class="flex flex-col gap-1 mb-6">
            <h1 class="text-3xl text-center font-bold">EMPLOI DU TEMPS</h1>
            <h2 class="text-lg text-center">
                {{ $promotion->annee_etude }}<sup>{{ $promotion->annee_etude == 1 ? 'ère' : 'ème' }}</sup> Année - 
                 {{ $selectedgroupe }} - 
                {{ $promotion->programme->nom }} / {{ $promotion->niveau->nom }}
            </h2>
        </div>
        <table class="w-full border-2 border-black table-fixed text-sm">
            <thead>
                <tr class="divide-x-2 divide-black border-b-2 border-black">
                    <th scope="col" class="py-1 px-2 text-start text-black w-24"></th>
                    @foreach ($timeSlots as $slot)
                        <th scope="col" class="py-1 px-2 text-center text-black">{{ $slot }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($classes->groupBy('jour') as $jour => $classes2)
                    <tr class="divide-x-2 divide-black border-b-2 border-black">
                        <td class="py-1 px-2 text-lg font-bold text-black capitalize">{{ $jour }}</td>

                        @foreach ($timeSlots as $slot)
                            @php
                                $classeInSlot = $classes2->first(function($c) use ($slot) {
                                    return ($c->heure_debut . ' - ' . $c->heure_fin) == $slot;
                                });
                            @endphp
                            
                            @if ($classeInSlot)
                                <td class="py-1 px-2 text-center">
                                    <div class="font-semibold text-black">{{ $classeInSlot->matiere->nom }}</div>
                                    <div class="text-black">{{ $classeInSlot->professeur->name }}</div>
                                    <div class="text-xs text-black italic">Salle: {{ $classeInSlot->salle }}</div>
                                </td>
                            @else
                                <td class="py-1 px-2 text-center"></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(function() {
        // Extract only the schedule div
        var content = document.getElementById('schedule-print-area').innerHTML;
        
        // Create a hidden iframe
        var iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);
        
        var doc = iframe.contentWindow.document;
        doc.open();
        
        // Copy all styles from the main page to ensure formatting is preserved
        var styles = document.querySelectorAll('style, link[rel="stylesheet"]');
        var styleHtml = '';
        styles.forEach(function(s) { styleHtml += s.outerHTML; });
        
        // Write the content to the iframe
        doc.write('<html><head>' + styleHtml + '</head><body class="bg-white">' + content + '</body></html>');
        doc.close();
        
        // Focus and print after a slight delay to allow styles to load
        iframe.contentWindow.focus();
        setTimeout(function() {
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 800);
        
    }, 1000); // 1 second delay to ensure initial rendering is fully complete
</script>
