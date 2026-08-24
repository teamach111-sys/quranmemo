<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasAnneeScolaire, SoftDeletes, HasFactory;

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
