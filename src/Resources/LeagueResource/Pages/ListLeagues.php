<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;

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
