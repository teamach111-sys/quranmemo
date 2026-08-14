<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'capacite',
    ];
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
