<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class LeagueSelect
{
    public static function options(Closure $get, Closure $set, ?Player $record): Collection
    {
        $federationId = $get('federation_id');

        if (! $record) {
            if (! $federationId) {
                return collect([]);
            }

            return self::collection($federationId);
        }

        $recordFederationId = $record->league
            ?->federation
            ?->id;

        if ($federationId === null) {
            $set('federation_id', $recordFederationId);
            $federationId = $recordFederationId;
        }

        return self::collection($federationId, $recordFederationId, $record);
    }

    protected static function collection(int $federationId, ?int $recordFederationId = null, ?Player $player = null): Collection
    {
        if (
            ! $player
            || $recordFederationId !== $federationId

        ) {
            return Federation::with('leagues')
                ->find($federationId)
                ?->leagues
                ?->pluck('name', 'id') ?? collect([]);
        }

        return $player->league
            ?->federation
            ?->leagues
            ?->pluck('name', 'id')
            ?? collect([]);
    }
}
