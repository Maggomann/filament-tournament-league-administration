<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeague extends CreateRecord
{
    protected static string $resource = LeagueResource::class;
}
