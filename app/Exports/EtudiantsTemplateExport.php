<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EtudiantsTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'Nom *',
            'Prénom *',
            'Sexe (M/F) *',
            'Date de naissance (AAAA-MM-JJ)',
            'Téléphone',
            'Email',
            'Adresse',
            'Nom du parent',
            'Téléphone du parent',
            'Relation (Père/Mère/Tuteur)',
        ];
    }

    public function array(): array
    {
        // One example row to guide the user
        return [
            [
                'Dupont',
                'Ahmed',
                'M',
                '2010-05-15',
                '0612345678',
                'ahmed@exemple.com',
                '12 Rue de la Paix',
                'Dupont Mohamed',
                '0698765432',
                'Père',
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
