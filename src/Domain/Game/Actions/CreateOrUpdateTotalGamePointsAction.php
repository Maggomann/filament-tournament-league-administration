<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Throwable;

class CreateOrUpdateTotalGamePointsAction
{
    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            DB::transaction(function () use ($game) {
                collect([
                    $game->homeTeam,
                    $game->guestTeam,
                ])->each(function (Team $team) use ($game) {
                    $totalTeamPoint = app(FirstOrCreateTotalTeamPointAction::class)->execute(
                        $team,
                        $game->gameSchedule
                    );

                    $totalTeamPointData = TotalTeamPointData::createFromTotalTeamPointWithRecalculation($totalTeamPoint);

                    app(UpdateTotalTeamPointsAction::class)->execute($totalTeamPoint, $totalTeamPointData);
                });
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
