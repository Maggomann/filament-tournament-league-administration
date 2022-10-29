<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Throwable;

class CreateOrUpdateTotalGamePointsAction
{
    protected Game $game;

    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            $this->game = $game;

            DB::transaction(function () {
                collect([
                    $this->game->homeTeam,
                    $this->game->guestTeam,
                ])->each(function (Team $team) {
                    $totalTeamPoint = app(FirstOrCreateTotalTeamPointAction::class)->execute(
                        $team,
                        $this->game->gameSchedule
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
