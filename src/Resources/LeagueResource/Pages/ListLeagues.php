<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeagues extends ListRecords
{
    protected static string $resource = LeagueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
