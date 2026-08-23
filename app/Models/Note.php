<?php

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasAnneeScolaire, SoftDeletes;
    protected $fillable = [
        'promotion_id',
        'etudiant_id',
        'note',
        'observation'
    ];
}
