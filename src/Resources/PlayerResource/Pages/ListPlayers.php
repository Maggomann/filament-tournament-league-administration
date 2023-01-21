<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
