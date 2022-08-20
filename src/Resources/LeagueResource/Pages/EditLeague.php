<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeague extends EditRecord
{
    protected static string $resource = LeagueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
