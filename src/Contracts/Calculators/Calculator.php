<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators;

use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

class Calculator extends Manager
{
    protected TotalTeamPoint $totalTeamPoint;

    public function __construct()
    {
        parent::__construct(app());
    }

    public static function make(TotalTeamPoint $totalTeamPoint): Request
    {
        $totalTeamPoint->load('gameSchedule.federation.calculationType');

        $instance = new static();
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
