<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\Team\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Throwable;

class UpdateTeamAction
{
    /**
     * @throws Throwable
     */
    public function execute(Team $team, TeamData $teamData): Team
    {
        try {
            return DB::transaction(function () use ($team, $teamData) {
                $team->fill($teamData->toArray());
                $team->league_id = $teamData->league_id;

                $team->save();

                return $team;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
