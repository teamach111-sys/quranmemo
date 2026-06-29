<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'description',
        'annee_scolaire_id',
        'professeur_id',
        'programme_id',
        'jour',
        'heure_debut',
        'heure_fin',
    ];

    public function annee_scolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function students()
    {
        return $this->hasMany(Etudiant::class);
    }
}
