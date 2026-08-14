<?php

namespace App\Models;
use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groupe extends Model
{
    //
    use SoftDeletes, HasAnneeScolaire;
    protected $fillable = [
        'nom',    
    ];
    
  
}
