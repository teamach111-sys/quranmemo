<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuiviTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'Nom *',
            'Prénom *',
            'Sourate *',
            'Debut',
            'Fin',
            'Juz',
            'Hizb',
            'Etat de recitation',
            'Observation',
        ];
    }

    public function array(): array
    {
        // One example row to guide the user
        return [
            [
                'Tazi',
                'Amine',
                1,
                1,
                7,
                1,
                1,
                'en_cours',
                'Exemple de suivi',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: bold white text on blue
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Example row: italic gray text
            2 => [
                'font' => ['italic' => true, 'color' => ['argb' => 'FF888888']],
            ],
        ];
    }
}