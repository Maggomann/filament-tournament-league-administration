<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Throwable;

class UpdateOrCreateTeamAction
{
    /**
     * @throws Throwable
     */
    public function execute(TeamData $teamData, ?Team $team = null): Team
    {
        try {
            return DB::transaction(function () use ($teamData, $team) {
                if (is_null($team)) {
                    $team = new Team();
                }

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
