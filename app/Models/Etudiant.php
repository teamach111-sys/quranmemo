<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etudiant extends Model
{
    use HasAnneeScolaire, SoftDeletes, HasFactory;

    protected $fillable = [
        'nom',
        'annee_scolaire_id',
        'groupe_id',
        'promotion_id',
        'prenom',
        'photo',
        'sexe',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'parent_nom',
        'parent_telephone',
        'parent_relation',
        'est_actif',
    ];

    public function promotion()
    {
        return $this->belongsTo(promotion::class);
    }

    public function classes()
    {
        return $this->hasMany(classe::class, 'promotion_id', 'promotion_id');
    }
    public function anneeScolaire(){
        return $this->belongsTo(AnneeScolaire::class);
    }
    public function matiere(){
        return $this->hasManyThrough(matiere::class, 'promotion_id', 'niveau_id' );
    }
}
