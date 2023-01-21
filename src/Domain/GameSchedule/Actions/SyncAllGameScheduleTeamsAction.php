<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class SyncAllGameScheduleTeamsAction
{
    public function execute(GameSchedule $gameSchedule): GameSchedule
    {
        return DB::transaction(function () use ($gameSchedule) {
            $gameSchedule->teams()->sync(
                $gameSchedule->league->teams()->pluck('id')
            );

            return $gameSchedule;
        });
    }
}
