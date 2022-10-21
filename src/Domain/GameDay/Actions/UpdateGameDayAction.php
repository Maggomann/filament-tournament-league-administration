<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\DTO\gameDayData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Throwable;

class UpdateGameDayAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameDay $gameDay, GameDayData $gameDayData): GameDay
    {
        try {
            return DB::transaction(function () use ($gameDay, $gameDayData) {
                $gameDay->fill($gameDayData->toArray());
                $gameDay->game_schedule_id = $gameDayData->game_schedule_id;

                $gameDay->save();

                return $gameDay;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}