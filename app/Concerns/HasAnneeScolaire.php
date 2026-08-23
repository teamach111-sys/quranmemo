<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasAnneeScolaire
{
    public function scopeForCurrentAnnee(Builder $query): Builder
    {
        $anneeId = session('selected_annee_id');

        return $query->when($anneeId, fn ($q) => $q->where('annee_scolaire_id', $anneeId));
    }
}
