<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class ListGameSchedules extends ListRecords
{
    protected static string $resource = GameScheduleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
