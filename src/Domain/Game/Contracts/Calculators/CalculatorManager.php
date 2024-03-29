<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators;

use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

final class CalculatorManager extends Manager
{
    protected TotalTeamPoint $totalTeamPoint;

    public function __construct()
    {
        parent::__construct(app());
    }

    public static function make(TotalTeamPoint $totalTeamPoint): Calculator
    {
        $totalTeamPoint->load('gameSchedule.federation.calculationType');

        $instance = new self();
        $instance->totalTeamPoint = $totalTeamPoint;

        return $instance->driver(
            Str::of($totalTeamPoint->gameSchedule->federation->calculationType->calculator)->explode('\\')->last()
        );
    }

    public function getDefaultDriver()
    {
        return 'HDLCalculator';
    }

    protected function createHDLCalculatorDriver(): HDLCalculator
    {
        return new HDLCalculator($this->totalTeamPoint);
    }

    protected function createDSABCalculatorDriver(): DSABCalculator
    {
        return new DSABCalculator($this->totalTeamPoint);
    }
}
