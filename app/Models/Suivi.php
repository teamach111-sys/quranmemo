<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasAnneeScolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suivi extends Model
{
    use HasAnneeScolaire, SoftDeletes;
}
