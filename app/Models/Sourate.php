<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sourate extends Model
{
    protected $fillable = [
        'number',
        'name_arabic',
        'name_complex',
        'name_simple',
        'name_french',
        'verses_count',
        'revelation_place',
        'revelation_order',
        'bismillah_pre',
    ];

    protected $casts = [
        'bismillah_pre' => 'boolean',
        'verses_count' => 'integer',
        'revelation_order' => 'integer',
    ];

    public function suivis(): HasMany
    {
        return $this->hasMany(Suivi::class);
    }
}