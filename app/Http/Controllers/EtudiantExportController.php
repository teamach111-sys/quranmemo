<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\EtudiantsExport;
use App\Models\Groupe;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EtudiantExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $promotionId = $request->filled('promotion') ? (int) $request->input('promotion') : null;
        $groupeId = $request->filled('groupe') ? (int) $request->input('groupe') : null;

        $export = new EtudiantsExport(
            promotionId: $promotionId,
            groupeId: $groupeId,
            search: $request->filled('search') ? $request->input('search') : null,
        );

        // Build filename with promotion and groupe names
        $parts = ['etudiants'];

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

        $parts[] = now()->format('Y-m-d');
        $filename = implode('_', $parts) . '.xlsx';

        return Excel::download($export, $filename);
    }
}
