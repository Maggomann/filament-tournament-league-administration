<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\DTO\GameDayData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Throwable;

class CreateGameDayAction
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
