<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Throwable;

class UpdateGameScheduleAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        try {
            return DB::transaction(function () use ($gameSchedule, $gameScheduleData) {
                $gameSchedule->fill($gameScheduleData->toArray());
                $gameSchedule->federation_id = $gameScheduleData->federation_id;
                $gameSchedule->leagueable_type = $gameScheduleData->leagueable_type;
                $gameSchedule->leagueable_id = $gameScheduleData->leagueable_id;

                $gameSchedule->save();

                return $gameSchedule;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
