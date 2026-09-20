<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\SuiviTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class SuiviTemplateController extends Controller
{
    public function __invoke()
    {
        return Excel::download(new SuiviTemplateExport(), 'modele_import_suivi.xlsx');
    }
}