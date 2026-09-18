<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EtudiantsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected ?int $promotionId = null,
        protected ?int $groupeId = null,
        protected ?string $search = null,
    ) {}

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Etudiant::query()
            ->select('*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) as age')
            ->forCurrentAnnee()
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                    ->orWhere('prenom', 'like', "%{$this->search}%")
                    ->orWhere('telephone', 'like', "%{$this->search}%");
            }))
            ->when($this->promotionId, fn ($q) => $q->where('promotion_id', $this->promotionId))
            ->when($this->groupeId, fn ($q) => $q->where('groupe_id', $this->groupeId))
            ->orderBy('nom')
            ->orderBy('prenom');
    }

    public function headings(): array
    {
        return [
            '#',
            'Nom',
            'Prénom',
            'Sexe',
            'Date de naissance',
            'Âge',
            'Téléphone',
            'Email',
            'Adresse',
            'Parent / Tuteur',
            'Tél. Parent',
            'Relation',
            'Statut',
        ];
    }

    public function map($etudiant): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $etudiant->nom,
            $etudiant->prenom,
            $etudiant->sexe,
            $etudiant->date_naissance,
            $etudiant->age,
            $etudiant->telephone,
            $etudiant->email,
            $etudiant->adresse,
            $etudiant->parent_nom,
            $etudiant->parent_telephone,
            $etudiant->parent_relation,
            $etudiant->est_actif ? 'Actif' : 'Inactif',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Bold header row with blue background and white text
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
