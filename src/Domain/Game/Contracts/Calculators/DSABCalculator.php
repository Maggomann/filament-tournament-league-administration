<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators;

use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class DSABCalculator implements Calculator
{
    protected int $totalWinMultiplikator = 3;

    protected int $totalDrawMultiplikator = 1;

    protected int $totalVictoryAfterDefeatsMultiplikator = 1;

    public function __construct(public TotalTeamPoint $totalTeamPoint)
    {
    }

    public function recalculate(): int
    {
        return $this->totalTeamPoint->total_wins * $this->totalWinMultiplikator
            + $this->totalTeamPoint->total_draws * $this->totalDrawMultiplikator
            + $this->totalTeamPoint->total_victory_after_defeats * $this->totalVictoryAfterDefeatsMultiplikator;
    }
}
