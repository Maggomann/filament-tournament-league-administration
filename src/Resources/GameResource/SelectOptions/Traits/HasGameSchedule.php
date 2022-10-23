<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\Traits;

use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

trait HasGameSchedule
{
    protected static function gameSchedule(int $gameScheduleId): ?GameSchedule
    {
        return once(fn () => GameSchedule::with(['days', 'teams'])->find($gameScheduleId));
    }
}
