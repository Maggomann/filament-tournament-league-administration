<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;

class LeagueSelect
{
    public static function options(Closure $get, Closure $set, ?Team $record): Collection
    {
        $federationId = $get('federation_id');

        if ($record && $federationId === null) {
            $federationId = $record->league?->federation?->id;

            $set('federation_id', $federationId);
        }

        if ($federationId === null) {
            return League::all()->pluck('name', 'id');
        }

        return Federation::with('leagues')
            ->find($federationId)
            ?->leagues
            ?->pluck('name', 'id');
    }
}
