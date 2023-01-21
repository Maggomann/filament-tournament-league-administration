<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Maggomann\FilamentTournamentLeagueAdministration\FilamentTournamentLeagueAdministration
 */
class FilamentTournamentLeagueAdministration extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-tournament-league-administration';
    }
}
