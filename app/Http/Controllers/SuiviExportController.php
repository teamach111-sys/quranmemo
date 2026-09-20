<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\SuiviExport;
use App\Models\AnneeScolaire;
use App\Models\Groupe;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SuiviExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $promotionId = $request->filled('promotion') ? (int) $request->input('promotion') : null;
        $groupeId = $request->filled('groupe') ? (int) $request->input('groupe') : null;
        $date = $request->filled('date') ? $request->input('date') : now()->toDateString();
        $anneeId = $request->filled('annee')
            ? (int) $request->input('annee')
            : (session('selected_annee_id') ?? AnneeScolaire::where('est_en_cours', true)->value('id'));

        $export = new SuiviExport(
            anneeScolaireId: (int) $anneeId,
            date: $date,
            promotionId: $promotionId,
            groupeId: $groupeId,
        );

        // Build filename with promotion and groupe names
        $parts = ['suivi'];

        if ($promotionId) {
            $promotion = Promotion::with('programme')->find($promotionId);
            if ($promotion?->programme) {
                $parts[] = str_replace(' ', '_', $promotion->programme->nom);
            }
        }

        if ($groupeId) {
            $groupe = Groupe::find($groupeId);
            if ($groupe) {
                $parts[] = str_replace(' ', '_', $groupe->nom);
            }
        }

        $parts[] = $date;
        $filename = implode('_', $parts) . '.xlsx';

        return Excel::download($export, $filename);
    }
}