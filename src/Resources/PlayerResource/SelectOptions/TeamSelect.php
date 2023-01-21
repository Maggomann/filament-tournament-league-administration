<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class TeamSelect
{
    public static function options(Closure $get, Closure $set, ?Player $record): Collection
    {
        $leagueId = $get('league_id');
        $federationId = $get('federation_id');

        if (! $federationId) {
            return collect([]);
        }

        if (! $record) {
            if (! $leagueId) {
                return collect([]);
            }

            return self::collection($leagueId);
        }

        $recordLeagueId = $record->league?->id;

        if ($leagueId === null) {
            $set('league_id', $recordLeagueId);
            $leagueId = $recordLeagueId;
        }

        return self::collection($federationId, $recordLeagueId, $record);
    }

    protected static function collection(int $leagueId, ?int $recordLeagueId = null, ?Player $player = null): Collection
    {
        if (
            ! $player
            || $recordLeagueId !== $leagueId

        ) {
            return League::with('teams')
                ->find($leagueId)
                ?->teams
                ?->pluck('name', 'id')
                ?? collect([]);
        }

        return $player->league
            ?->teams
            ?->pluck('name', 'id')
            ?? collect([]);
    }
}
