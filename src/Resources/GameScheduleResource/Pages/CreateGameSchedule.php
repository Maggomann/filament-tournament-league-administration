<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class CreateGameSchedule extends CreateRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app($this->getModel());
    }
}
