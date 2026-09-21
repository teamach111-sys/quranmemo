<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Periode;
use App\Models\Promotion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NotePdfController extends Controller
{
    public function __invoke(Request $request)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');

        $promotionId = $request->filled('promotion') ? (int) $request->input('promotion') : null;
        $groupeId = $request->filled('groupe') ? (int) $request->input('groupe') : null;
        $periodeId = $request->filled('periode') ? (int) $request->input('periode') : null;
        $matiereId = $request->filled('matiere') ? (int) $request->input('matiere') : null;

        $students = Etudiant::query()
            ->forCurrentAnnee()
            ->when($promotionId, fn ($q) => $q->where('promotion_id', $promotionId))
            ->when($groupeId, fn ($q) => $q->where('groupe_id', $groupeId))
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $records = Note::whereIn('etudiant_id', $students->pluck('id'))
            ->when($promotionId, fn ($q) => $q->where('promotion_id', $promotionId))
            ->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId))
            ->when($matiereId, fn ($q) => $q->where('matiere_id', $matiereId))
            ->get()
            ->keyBy('etudiant_id');

        // Resolve display names
        $promotionNom = null;
        if ($promotionId) {
            $promotion = Promotion::with('programme')->find($promotionId);
            $promotionNom = $promotion?->programme?->nom;
        }

        $groupeNom = $groupeId ? Groupe::find($groupeId)?->nom : null;
        $periodeNom = $periodeId ? Periode::find($periodeId)?->nom : null;
        $matiereNom = $matiereId ? Matiere::find($matiereId)?->nom : null;

        // Summary stats
        $totalEtudiants = $students->count();
        $totalAvecNote = $records->count();

        $data = compact(
            'students',
            'records',
            'promotionNom',
            'groupeNom',
            'periodeNom',
            'matiereNom',
            'totalEtudiants',
            'totalAvecNote',
        );

        // Build filename
        $parts = ['notes'];
        if ($periodeNom) {
            $parts[] = str_replace(' ', '_', $periodeNom);
        }
        if ($matiereNom) {
            $parts[] = str_replace(' ', '_', $matiereNom);
        }
        if ($promotionNom) {
            $parts[] = str_replace(' ', '_', $promotionNom);
        }
        if ($groupeNom) {
            $parts[] = str_replace(' ', '_', $groupeNom);
        }
        $filename = implode('_', $parts) . '.pdf';

        $pdf = Pdf::loadView('print.note', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
