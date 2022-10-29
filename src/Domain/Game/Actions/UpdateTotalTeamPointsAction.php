<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators\CalculatorManager;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Throwable;

class UpdateTotalTeamPointsAction
{
    /**
     * @throws Throwable
     */
    public function execute(TotalTeamPoint $totalTeamPoint, TotalTeamPointData $totalTeamPointData): void
    {
        try {
            DB::transaction(function () use ($totalTeamPoint, $totalTeamPointData) {
                $totalTeamPoint->fill($totalTeamPointData->toArray());

                $totalTeamPoint->total_points = CalculatorManager::make($totalTeamPoint)->recalculate();
                $totalTeamPoint->save();
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
