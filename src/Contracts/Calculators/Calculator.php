<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators;

use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Relations\Relation;

class Calculator
{
    public static function getMorphClass()
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array(static::class, $morphMap)) {
            return array_search(static::class, $morphMap, true);
        }

        if (Relation::requiresMorphMap()) {
            throw new ClassMorphViolationException(new static());
        }

        return static::class;
    }
}
