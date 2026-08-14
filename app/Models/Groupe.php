<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'nom',    
    ];
    
  
}
