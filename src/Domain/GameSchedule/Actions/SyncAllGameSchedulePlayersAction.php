<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class SyncAllGameSchedulePlayersAction
{
    public function execute(GameSchedule $gameSchedule): GameSchedule
    {
        return DB::transaction(function () use ($gameSchedule) {
            $gameSchedule->players()->sync(
                Player::whereIn(
                    'tournament_league_players.team_id',
                    $gameSchedule->teams()->pluck('team_id')
                )->pluck('id')
            );

            return $gameSchedule;
        });
    }
}
