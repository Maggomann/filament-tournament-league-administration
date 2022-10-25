<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators;

use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\Traits\HasMorphClass;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class HDLCalculator implements Request
{
    use HasMorphClass;

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
