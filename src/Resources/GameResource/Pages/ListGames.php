<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;

class ListGames extends ListRecords
{
    protected static string $resource = GameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
