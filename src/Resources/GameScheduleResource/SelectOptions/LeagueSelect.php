<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class LeagueSelect
{
    public static function options(Closure $get, Closure $set, ?GameSchedule $record): Collection
    {
        $federationId = $get('federation_id');

        if (! $record) {
            if (! $federationId) {
                return collect([]);
            }

            return self::collection($federationId);
        }

        $recordFederationId = $record->federation?->id;

        if ($federationId === null) {
            $set('federation_id', $recordFederationId);
            $federationId = $recordFederationId;
        }

        return self::collection($federationId, $recordFederationId, $record);
    }

    protected static function collection(int $federationId, ?int $recordFederationId = null, ?GameSchedule $gameSchedule = null): Collection
    {
        if (
            ! $gameSchedule
            || $recordFederationId !== $federationId

        ) {
            return Federation::with('leagues')
                ->find($federationId)
                ?->leagues
                ?->pluck('name', 'id') ?? collect([]);
        }

        return $gameSchedule->federation
            ?->leagues
            ?->pluck('name', 'id')
            ?? collect([]);
    }
}
