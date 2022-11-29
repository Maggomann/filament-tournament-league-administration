<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class FirstOrCreateTotalTeamPointAction
{
    public function execute(Team $team, GameSchedule $gameSchedule): TotalTeamPoint
    {
        try {
            return TotalTeamPoint::query()
                        ->where('game_schedule_id', $gameSchedule->id)
                        ->where('team_id', $team->id)
                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return DB::transaction(function () use ($team, $gameSchedule) {
                $totalTeamPoint = new TotalTeamPoint();
                $totalTeamPoint->game_schedule_id = $gameSchedule->id;
                $totalTeamPoint->team_id = $team->id;

                $totalTeamPoint->save();

                return $totalTeamPoint;
            });
        }
    }
}
