<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Juz extends Model
{
    protected $fillable = [
        'number',
        'first_verse_id',
        'last_verse_id',
        'first_verse_key',
        'last_verse_key',
        'verses_count',
        'verse_mapping',
    ];

    protected $casts = [
        'verse_mapping' => 'array',
    ];

    public function hizbs(): HasMany
    {
        return $this->hasMany(Hizb::class);
    }
}