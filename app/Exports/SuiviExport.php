<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Etudiant;
use App\Models\Suivi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuiviExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $records = [];

    public function __construct(
        protected int $anneeScolaireId,
        protected string $date,
        protected ?int $promotionId = null,
        protected ?int $groupeId = null,
    ) {}

    public function collection(): Collection
    {
        $students = Etudiant::query()
            ->forCurrentAnnee()
            ->when($this->promotionId, fn ($q) => $q->where('promotion_id', $this->promotionId))
            ->when($this->groupeId, fn ($q) => $q->where('groupe_id', $this->groupeId))
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $this->records = Suivi::with('sourate', 'juz', 'hizb')
            ->whereIn('etudiant_id', $students->pluck('id'))
            ->where('date', $this->date)
            ->where('annee_scolaire_id', $this->anneeScolaireId)
            ->get()
            ->keyBy('etudiant_id')
            ->all();

        return $students;
    }

    public function headings(): array
    {
        return [
            '#',
            'Étudiant',
            'Sourate',
            'Début',
            'Fin',
            'Juz',
            'Hizb',
            'État de récitation',
            'Observation',
        ];
    }

    public function map($student): array
    {
        static $index = 0;
        $index++;

        $record = $this->records[$student->id] ?? null;

        return [
            $index,
            "{$student->prenom} {$student->nom}",
            $record && $record->sourate
                ? $record->sourate->number . '. ' . $record->sourate->name_simple
                : null,
            $record?->debut_aya,
            $record?->fin_aya,
            $record && $record->juz ? 'Juz ' . $record->juz->number : null,
            $record && $record->hizb ? 'Hizb ' . $record->hizb->number : null,
            $record ? (Suivi::ETATS[$record->etat_de_recitation] ?? $record->etat_de_recitation) : null,
            $record?->observation,
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