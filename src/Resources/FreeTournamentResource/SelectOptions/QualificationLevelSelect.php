<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\QualificationLevel;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\Traits\HasTranslatableKeyPrefix;

class QualificationLevelSelect
{
    use HasTranslatableKeyPrefix;

    public static function options(): Collection
    {
        return self::collection();
    }

    protected static function collection(): Collection
    {
        return QualificationLevel::all()->pluck('title_translation_key', 'id')
            ->mapWithKeys(fn ($value, $key) => [$key => __(static::$translatableKeyPrefix."{$value}")]);
    }
}
