<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;

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
