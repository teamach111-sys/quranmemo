<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model
{
    use SoftDeletes;

    protected $fillable = ['nom', 'description'];

    public function Classe()
    {
        return $this->hasMany(Classe::class);
    }
}
