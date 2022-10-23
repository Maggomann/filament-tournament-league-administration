<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Throwable;

class DeleteGameAction
{
    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            DB::transaction(function () use ($game): void {
                // TODO: delete the totals in tournament_league_total_team_points

                $game->delete();
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
