<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators;

interface Calculator
{
    public function recalculate(): int;
}
