<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use HasAnneeScolaire, SoftDeletes;

    protected $fillable = [
        'promotion_id',
        'periode_id',
        'matiere_id',
        'professeur_id',
        'groupe_id',
        'salle',
        'jour',
        'heure_debut',
        'heure_fin',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'classe_etudiant');
    }

    public function getNiveauAttribute()
    {
        return $this->matiere?->niveau;
    }

    public function getProgrammeAttribute()
    {
        return $this->matiere?->niveau?->programme;
    }

    public function getAnneeEtudeAttribute()
    {
        return $this->matiere?->annee_etude;
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
    public function groupe()
    {
        return $this->belongsTo(groupe::class);
    }
    public function periode()
    {
        return $this->belongsTo(periode::class);
    }
}
