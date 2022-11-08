<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Throwable;

class DetachGameScheduleTeamsAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule, Collection $records): void
    {
        try {
            DB::transaction(function () use ($gameSchedule, $records) {
                $gameSchedule->players()->detach(
                    $gameSchedule->players()->whereIn(
                        'tournament_league_players.team_id',
                        $records->pluck('id')
                    )->pluck('id')
                );

                $gameSchedule->teams()->detach($records->pluck('id'));
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
