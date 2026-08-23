<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groupe extends Model
{
    //
    use HasAnneeScolaire, SoftDeletes;

    protected $fillable = [
        'nom',
        'annee_scolaire_id',
    ];

    public function anneescolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}
