<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Throwable;

class SyncAllGameSchedulePlayersAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule): GameSchedule
    {
        try {
            return DB::transaction(function () use ($gameSchedule) {
                $gameSchedule->players()->sync(
                    Player::whereIn(
                        'tournament_league_players.team_id',
                        $gameSchedule->teams()->pluck('id')
                    )->pluck('id')
                );

                return $gameSchedule;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
