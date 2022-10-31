<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Mode;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\Traits\HasTranslatableKeyPrefix;

class ModeSelect
{
    use HasTranslatableKeyPrefix;

    public static function options(): Collection
    {
        return self::collection();
    }

    protected static function collection(): Collection
    {
        return Mode::all()->pluck('title_translation_key', 'id')
                ->mapWithKeys(fn ($value, $key) => [$key => __(static::$translatableKeyPrefix."{$value}")]);
    }
}
