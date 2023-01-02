<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class UpdateTotalTeamPointPlacementsAction
{
    public function execute(GameSchedule $gameSchedule): void
    {
        $updateData = $gameSchedule->totalTeamPoints()
            ->orderBy('total_points')
            ->get()
            ->mapWithKeys(function (TotalTeamPoint $totalTeamPoint, $key) {
                return [
                    $totalTeamPoint->id => [
                        'id' => $totalTeamPoint->id,
                        'placement' => $key + 1,
                    ],
                ];
            });

        if ($updateData->isNotEmpty()) {
            TotalTeamPoint::query()->upsert($updateData->all(), 'id');
        }
    }
}
