<?php

namespace App\Models;
use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salle extends Model
{
    
    use SoftDeletes, HasAnneeScolaire;
    protected $fillable = [
        'nom',
        'capacite',
    ];
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
