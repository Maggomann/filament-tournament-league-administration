<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators;

interface Request
{
    public function recalculate(): int;
}
