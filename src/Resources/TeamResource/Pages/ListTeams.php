<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
