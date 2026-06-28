<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'est_en_cours',
    ];


    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class);
    }
}
