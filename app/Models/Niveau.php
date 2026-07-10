<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Niveau extends Model
{
    use SoftDeletes;
    protected $fillable = ['nom','nombre_annees','programme_id'];

   
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
    public function matiere(){
        return $this->hasMany(Matiere::class);
    }
}
