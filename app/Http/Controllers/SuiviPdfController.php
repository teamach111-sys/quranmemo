<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\Promotion;
use App\Models\Suivi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuiviPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');

        $promotionId = $request->filled('promotion') ? (int) $request->input('promotion') : null;
        $groupeId = $request->filled('groupe') ? (int) $request->input('groupe') : null;
        $date = $request->filled('date') ? $request->input('date') : now()->toDateString();
        $anneeId = $request->filled('annee')
            ? (int) $request->input('annee')
            : (session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id'));

        $students = Etudiant::query()
            ->forCurrentAnnee()
            ->when($promotionId, fn ($q) => $q->where('promotion_id', $promotionId))
            ->when($groupeId, fn ($q) => $q->where('groupe_id', $groupeId))
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $records = Suivi::with('sourate', 'juz', 'hizb')
            ->whereIn('etudiant_id', $students->pluck('id'))
            ->where('date', $date)
            ->where('annee_scolaire_id', $anneeId)
            ->get()
            ->keyBy('etudiant_id');

        // Resolve display names
        $promotionNom = null;
        if ($promotionId) {
            $promotion = Promotion::with('programme')->find($promotionId);
            $promotionNom = $promotion?->programme?->nom;
        }

        $groupeNom = $groupeId ? Groupe::find($groupeId)?->nom : null;

        $anneeScolaire = AnneeScolaire::find($anneeId)?->libelle;

        // Summary stats
        $totalEtudiants = $students->count();
        $totalAvecSuivi = $records->count();

        $data = compact(
            'students',
            'records',
            'promotionNom',
            'groupeNom',
            'anneeScolaire',
            'date',
            'totalEtudiants',
            'totalAvecSuivi',
        );

        // Build filename
        $parts = ['suivi'];
        if ($promotionNom) {
            $parts[] = str_replace(' ', '_', $promotionNom);
        }
        if ($groupeNom) {
            $parts[] = str_replace(' ', '_', $groupeNom);
        }
        $parts[] = $date;
        $filename = implode('_', $parts) . '.pdf';

        $pdf = Pdf::loadView('print.suivi', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}