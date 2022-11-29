<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class DetachGameScheduleTeamsAction
{
    public function execute(GameSchedule $gameSchedule, Collection $records): void
    {
        DB::transaction(function () use ($gameSchedule, $records) {
            $gameSchedule->players()->detach(
                $gameSchedule->players()->whereIn(
                    'tournament_league_players.team_id',
                    $records->pluck('id')
                )->pluck('id')
            );

            $gameSchedule->teams()->detach($records->pluck('id'));
        });
    }
}
