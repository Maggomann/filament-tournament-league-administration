<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions\CreateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class CreateGameSchedule extends CreateRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateGameScheduleAction::class)->execute(
            app($this->getModel()),
            GameScheduleData::create($data)
        );

        return app($this->getModel());
    }
}
