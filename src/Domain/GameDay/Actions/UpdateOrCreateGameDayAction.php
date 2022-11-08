<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\DTO\GameDayData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Throwable;

class UpdateOrCreateGameDayAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameDayData $gameDayData, GameDay $gameDay): GameDay
    {
        try {
            return DB::transaction(function () use ($gameDayData, $gameDay) {
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
