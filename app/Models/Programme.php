<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model
{
    use HasAnneeScolaire, SoftDeletes, HasFactory;

    protected $fillable = ['nom', 'description'];

    public function Classe()
    {
        return $this->hasMany(Classe::class);
    }

    public function niveaux()
    {
        return $this->hasMany(Niveau::class);
    }

    public function matiere()
    {
        return $this->hasManyThrough(Matiere::class, Niveau::class);
    }
}
