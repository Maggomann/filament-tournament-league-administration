<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class DeleteGameScheduleAction
{
    public function execute(GameSchedule $gameSchedule): void
    {
        DB::transaction(function () use ($gameSchedule): void {
            $gameSchedule->days()->delete();
            $gameSchedule->teams()->delete();
            $gameSchedule->players()->delete();
            $gameSchedule->games()->delete();
            $gameSchedule->totalTeamPoints()->delete();

            $gameSchedule->delete();
        });
    }
}
