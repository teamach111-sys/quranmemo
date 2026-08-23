<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Niveau extends Model
{
    use HasAnneeScolaire, SoftDeletes;

    protected $fillable = ['nom', 'nombre_annees', 'programme_id'];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function matiere()
    {
        return $this->hasMany(Matiere::class);
    }
}
