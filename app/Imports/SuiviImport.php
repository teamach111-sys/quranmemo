<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Hizb;
use App\Models\Juz;
use App\Models\Sourate;
use App\Models\Suivi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SuiviImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(
        protected int $anneeScolaireId,
        protected string $date,
        protected int $promotionId,
        protected ?int $groupeId = null,
    ) {}

    public function model(array $row): ?Suivi
    {
        // Skip empty rows or the example row
        $nom = trim((string) ($row['nom'] ?? ''));
        $prenom = trim((string) ($row['prenom'] ?? ''));
        $sourateNumber = (int) ($row['sourate'] ?? 0);

        if ($nom === '' || $prenom === '' || !$sourateNumber) {
            return null;
        }

        // Match the student by name inside the selected promotion/groupe
        $student = Etudiant::where('promotion_id', $this->promotionId)
            ->when($this->groupeId, fn ($q) => $q->where('groupe_id', $this->groupeId))
            ->whereRaw('LOWER(nom) = ? AND LOWER(prenom) = ?', [strtolower($nom), strtolower($prenom)])
            ->first();

        if (!$student) {
            return null;
        }

        $sourate = Sourate::where('number', $sourateNumber)->first();
        if (!$sourate) {
            return null;
        }

        $juz = ($row['juz'] ?? null) ? Juz::where('number', (int) $row['juz'])->first() : null;
        $hizb = ($row['hizb'] ?? null) ? Hizb::where('number', (int) $row['hizb'])->first() : null;

        return Suivi::updateOrCreate(
            [
                'etudiant_id' => $student->id,
                'date' => $this->date,
                'annee_scolaire_id' => $this->anneeScolaireId,
            ],
            [
                'classe_id' => null,
                'isArchived' => false,
                'observation' => $row['observation'] ?? null,
                'sourate_id' => $sourate->id,
                'debut_aya' => ($row['debut'] ?? null) ? (int) $row['debut'] : null,
                'fin_aya' => ($row['fin'] ?? null) ? (int) $row['fin'] : null,
                'juz_id' => $juz?->id,
                'hizb_id' => $hizb?->id,
                'etat_de_recitation' => $this->normalizeEtat($row['etat_de_recitation'] ?? 'en_cours'),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'sourate' => 'required|integer',
            'debut' => 'nullable|integer|min:1',
            'fin' => 'nullable|integer|min:1',
            'juz' => 'nullable|integer|min:1',
            'hizb' => 'nullable|integer|min:1',
        ];
    }

    private function normalizeEtat(?string $value): string
    {
        $value = strtolower(trim((string) $value));

        if (array_key_exists($value, Suivi::ETATS)) {
            return $value;
        }

        foreach (Suivi::ETATS as $key => $label) {
            if (strtolower($label) === $value) {
                return $key;
            }
        }

        return 'en_cours';
    }
}