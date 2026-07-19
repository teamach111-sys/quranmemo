<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'annee_scolaire_id',
        'programme_id',
        'niveau_id',
        'annee_etude',
    ];

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
}