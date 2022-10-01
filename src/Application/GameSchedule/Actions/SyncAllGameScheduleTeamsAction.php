<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Throwable;

class SyncAllGameScheduleTeamsAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule): GameSchedule
    {
        try {
            return DB::transaction(function () use ($gameSchedule) {
                $gameSchedule->teams()->sync(
                    $gameSchedule->leagueBT->teams()->pluck('id')
                );

                return $gameSchedule;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
