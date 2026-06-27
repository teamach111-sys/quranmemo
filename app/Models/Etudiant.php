<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Etudiant extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nom',
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
}
