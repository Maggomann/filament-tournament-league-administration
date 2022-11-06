<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use RuntimeException;

trait HasMorphClass
{
    public static function getMorphClass(): string
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array(static::class, $morphMap)) {
            return array_search(static::class, $morphMap, true);
        }

        if (Relation::requiresMorphMap()) {
            $class = static::class;

            throw new RuntimeException("No morph map defined for model [{$class}].");
        }

        return static::class;
    }
}
