<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Throwable;

class CreateGameScheduleAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        try {
            return DB::transaction(function () use ($gameSchedule, $gameScheduleData) {
                $gameSchedule = $this->createGameSchedule($gameSchedule, $gameScheduleData);
                $gameSchedule = $this->createGameDays($gameSchedule, $gameScheduleData);

                return $gameSchedule;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function createGameSchedule(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        $gameSchedule->fill($gameScheduleData->toArray());
        $gameSchedule->federation_id = $gameScheduleData->federation_id;
        $gameSchedule->gameschedulable_type = $gameScheduleData->gameschedulable_type;
        $gameSchedule->gameschedulable_id = $gameScheduleData->gameschedulable_id;

        $gameSchedule->save();

        return $gameSchedule;
    }

    private function createGameDays(GameSchedule $gameSchedule, GameScheduleData $gameScheduleData): GameSchedule
    {
        $gameDays = collect()->times($gameScheduleData->game_days, function ($day) use ($gameSchedule) {
            $gameDay = new GameDay();
            $gameDay->game_schedule_id = $gameSchedule->id;
            $gameDay->day = $day;

            return $gameDay;
        });

        $gameSchedule->days()->saveMany($gameDays);

        return $gameSchedule;
    }
}
