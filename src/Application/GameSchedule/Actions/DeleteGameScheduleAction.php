<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Throwable;

class DeleteGameScheduleAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule): void
    {
        try {
            DB::transaction(function () use ($gameSchedule): void {
                $gameSchedule->days()->delete();
                $gameSchedule->teams()->delete();
                $gameSchedule->players()->delete();

                $gameSchedule->delete();
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
