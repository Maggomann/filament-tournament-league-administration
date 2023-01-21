<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators;

use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class HDLCalculator implements Calculator
{
    protected int $totalWinMultiplikator = 2;

    protected int $totalDrawMultiplikator = 2;

    public function __construct(public TotalTeamPoint $totalTeamPoint)
    {
    }

    public function recalculate(): int
    {
        return $this->totalTeamPoint->total_wins * $this->totalWinMultiplikator
         + $this->totalTeamPoint->total_draws * $this->totalDrawMultiplikator;
    }
}
