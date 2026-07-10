<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matiere extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'niveau_id',
        'description',
        'annee_etude',
    ];


    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    

}
