<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EtudiantsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(
        protected int $anneeScolaireId,
        protected ?int $promotionId = null,
        protected ?int $groupeId = null,
    ) {}

    public function model(array $row): ?Etudiant
    {
        // Skip empty rows or the example row
        $nom = trim((string) ($row['nom'] ?? ''));
        $prenom = trim((string) ($row['prenom'] ?? ''));

        if ($nom === '' || $prenom === '') {
            return null;
        }

        return new Etudiant([
            'nom' => $nom,
            'prenom' => $prenom,
            'sexe' => strtoupper(trim((string) ($row['sexe_mf'] ?? 'M'))),
            'date_naissance' => $row['date_de_naissance_aaaa-mm-jj'] ?? $row['date_de_naissance_aaaa_mm_jj'] ?? null,
            'telephone' => $row['telephone'] ?? null,
            'email' => $row['email'] ?? null,
            'adresse' => $row['adresse'] ?? null,
            'parent_nom' => $row['nom_du_parent'] ?? null,
            'parent_telephone' => $row['telephone_du_parent'] ?? null,
            'parent_relation' => $row['relation_peremeretuteur'] ?? $row['relation_pere_mere_tuteur'] ?? null,
            'annee_scolaire_id' => $this->anneeScolaireId,
            'promotion_id' => $this->promotionId,
            'groupe_id' => $this->groupeId,
            'est_actif' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe_mf' => 'nullable|string|in:M,F,m,f',
        ];
    }
}
