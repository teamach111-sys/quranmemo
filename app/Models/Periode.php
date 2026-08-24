<?php

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasAnneeScolaire;

    protected $fillable = ['nom'];


    public function matiere(){
        return $this->hasMany(matiere::class);
    }
}
