<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hizb extends Model
{
    protected $fillable = [
        'number',
        'juz_id',
        'first_verse_id',
        'last_verse_id',
        'first_verse_key',
        'last_verse_key',
        'verses_count',
    ];

    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class);
    }
}