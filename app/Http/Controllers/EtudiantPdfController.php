<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\Promotion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EtudiantPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        
        $promotionId = $request->filled('promotion') ? (int) $request->input('promotion') : null;
        $groupeId = $request->filled('groupe') ? (int) $request->input('groupe') : null;
        $search = $request->filled('search') ? $request->input('search') : null;

        $etudiants = Etudiant::query()
            ->select('*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) as age')
            ->forCurrentAnnee()
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            }))
            ->when($promotionId, fn ($q) => $q->where('promotion_id', $promotionId))
            ->when($groupeId, fn ($q) => $q->where('groupe_id', $groupeId))
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        // Resolve display names
        $promotionNom = null;
        if ($promotionId) {
            $promotion = Promotion::with('programme')->find($promotionId);
            $promotionNom = $promotion?->programme?->nom;
        }

        $groupeNom = $groupeId ? Groupe::find($groupeId)?->nom : null;

        $anneeScolaire = AnneeScolaire::where('est_en_cours', true)->value('libelle')
            ?? session('selected_annee_id');

        // Summary stats
        $totalEtudiants = $etudiants->count();
        $totalActifs = $etudiants->where('est_actif', true)->count();
        $totalInactifs = $totalEtudiants - $totalActifs;
        $totalGarcons = $etudiants->where('sexe', 'M')->count();
        $totalFilles = $etudiants->where('sexe', 'F')->count();

        $data = compact(
            'etudiants',
            'promotionNom',
            'groupeNom',
            'anneeScolaire',
            'totalEtudiants',
            'totalActifs',
            'totalInactifs',
            'totalGarcons',
            'totalFilles',
        );

        // Build filename
        $parts = ['etudiants'];
        if ($promotionNom) {
            $parts[] = str_replace(' ', '_', $promotionNom);
        }
        if ($groupeNom) {
            $parts[] = str_replace(' ', '_', $groupeNom);
        }
        $parts[] = now()->format('Y-m-d');
        $filename = implode('_', $parts) . '.pdf';

        $pdf = Pdf::loadView('print.etudiants', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
