<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\EtudiantsTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class EtudiantTemplateController extends Controller
{
    public function __invoke()
    {
        return Excel::download(new EtudiantsTemplateExport(), 'modele_import_etudiants.xlsx');
    }
}
