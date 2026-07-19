<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'promotion_id',
        'matiere_id',
        'professeur_id',
        'groupe',
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
        return $this->hasMany(Etudiant::class);
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
}