<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\Traits;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

trait HasTeamsCollection
{
    protected static function collection(int $gameScheduleId, array $otherTeamId, ?GameSchedule $gameSchedule = null): Collection
    {
        if (! $gameSchedule) {
            return self::gameSchedule($gameScheduleId)
                ?->teams
                ?->whereNotIn('id', $otherTeamId)
                ?->pluck('name', 'id') ?? collect([]);
        }

        return $gameSchedule
            ?->teams
            ?->whereNotIn('id', $otherTeamId)
            ?->pluck('name', 'id') ?? collect([]);
    }
}
