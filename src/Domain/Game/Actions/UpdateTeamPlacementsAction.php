<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class UpdateTeamPlacementsAction
{
    public function __construct(
        protected ?GameSchedule $gameSchedule = null,
        protected ?Collection $calculatedPlacementsData = null
    ) {
    }

    public function execute(GameSchedule $gameSchedule): void
    {
        $this->withGameSchedule($gameSchedule)
            ->calculatePlacementsData()
            ->savePlacements();
    }

    private function withGameSchedule(GameSchedule $gameSchedule): self
    {
        $this->gameSchedule = $gameSchedule;

        return $this;
    }

    private function calculatePlacementsData(): self
    {
        $this->calculatedPlacementsData = $this->gameSchedule
            ?->totalTeamPoints()
            ->orderByDesc('total_points')
            ->get()
            ->mapWithKeys(fn (TotalTeamPoint $totalTeamPoint, $key) => [
                $totalTeamPoint->id => [
                    'id' => $totalTeamPoint->id,
                    'placement' => $key + 1,
                ],
            ]);

        return $this;
    }

    private function savePlacements(): void
    {
        if ($this->calculatedPlacementsData?->isNotEmpty()) {
            TotalTeamPoint::query()->upsert($this->calculatedPlacementsData->all(), 'id');
        }
    }
}
