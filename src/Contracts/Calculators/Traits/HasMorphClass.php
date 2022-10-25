<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\Traits;

use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasMorphClass
{
    public static function getMorphClass(): string
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array(static::class, $morphMap)) {
            return array_search(static::class, $morphMap, true);
        }

        if (Relation::requiresMorphMap()) {
            throw new ClassMorphViolationException(new self());
        }

        return static::class;
    }
}
