<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class UpdateOrCreateGameScheduleAction
{
    public function execute(GameScheduleData $gameScheduleData, ?GameSchedule $gameSchedule = null): GameSchedule
    {
        return DB::transaction(function () use ($gameScheduleData, $gameSchedule) {
            if (is_null($gameSchedule)) {
                $gameSchedule = new GameSchedule();
            }

            $gameSchedule = $this->createGameSchedule($gameSchedule, $gameScheduleData);
            $gameSchedule = $this->createGameDaysIfNotAvailable($gameSchedule, $gameScheduleData);

            return $gameSchedule;
        });
    }

    private function createGameSchedule(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        $gameSchedule->fill($gameScheduleData->toArray());
        $gameSchedule->federation_id = $gameScheduleData->federation_id;
        $gameSchedule->league_id = $gameScheduleData->league_id;

        $gameSchedule->save();

        return $gameSchedule;
    }

    private function createGameDaysIfNotAvailable(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        if (! $gameScheduleData->game_days) {
            return $gameSchedule;
        }

        $gameDays = collect()->times($gameScheduleData->game_days, function (int $day) use ($gameSchedule): GameDay {
            $gameDay = new GameDay();
            $gameDay->game_schedule_id = $gameSchedule->id;
            $gameDay->day = $day;

            return $gameDay;
        });

        $gameSchedule->days()->saveMany($gameDays);

        return $gameSchedule;
    }
}
