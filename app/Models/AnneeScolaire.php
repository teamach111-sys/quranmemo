<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnneeScolaire extends Model
{
    use SoftDeletes;
     protected static function booted()
    {
       // static::deleting(function ($year) {
         //   $year->articles()->delete();      
        //});
    }
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
