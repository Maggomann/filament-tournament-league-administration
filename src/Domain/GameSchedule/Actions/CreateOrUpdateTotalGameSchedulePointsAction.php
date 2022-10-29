<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\FirstOrCreateTotalTeamPointAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateTotalTeamPointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Throwable;

class CreateOrUpdateTotalGameSchedulePointsAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameSchedule $gameSchedule): void
    {
        try {
            DB::transaction(function () use ($gameSchedule) {
                $gameSchedule->teams->each(function (Team $team) use ($gameSchedule) {
                    $totalTeamPoint = app(FirstOrCreateTotalTeamPointAction::class)->execute($team, $gameSchedule);

                    $totalTeamPointData = TotalTeamPointData::createFromTotalTeamPointWithRecalculation($totalTeamPoint);

                    app(UpdateTotalTeamPointsAction::class)->execute($totalTeamPoint, $totalTeamPointData);
                });
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
