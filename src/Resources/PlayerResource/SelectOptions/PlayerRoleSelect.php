<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\SelectOptions;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\PlayerRole;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\Traits\HasTranslatableKeyPrefix;

class PlayerRoleSelect
{
    use HasTranslatableKeyPrefix;

    public static function options(): Collection
    {
        return self::collection();
    }

    protected static function collection(): Collection
    {
        return once(function () {
            return PlayerRole::all()->pluck('title_translation_key', 'id')
                ->mapWithKeys(fn ($value, $key) => [$key => __(static::$translatableKeyPrefix."{$value}")]);
        });
    }

    public static function translatedById(?int $id = null): ?string
    {
        return once(function () use ($id) {
            if (blank($id)) {
                return null;
            }

            return static::collection()->get($id);
        });
    }
}
