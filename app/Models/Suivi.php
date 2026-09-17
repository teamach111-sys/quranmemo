<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suivi extends Model
{
    use HasAnneeScolaire, SoftDeletes;

    public const ETATS = [
        'en_cours' => 'En cours',
        'bien' => 'Bien',
        'tres_bien' => 'Très bien',
        'excellent' => 'Excellent',
        'a_revoir' => 'À revoir',
    ];

    protected $fillable = [
        'date',
        'classe_id',
        'etudiant_id',
        'isArchived',
        'observation',
        'sourate_id',
        'debut_aya',
        'fin_aya',
        'juz_id',
        'hizb_id',
        'annee_scolaire_id',
        'etat_de_recitation',
    ];

    protected $casts = [
        'isArchived' => 'boolean',
        'debut_aya' => 'integer',
        'fin_aya' => 'integer',
    ];

    public function sourate(): BelongsTo
    {
        return $this->belongsTo(Sourate::class);
    }

    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class);
    }

    public function hizb(): BelongsTo
    {
        return $this->belongsTo(Hizb::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}