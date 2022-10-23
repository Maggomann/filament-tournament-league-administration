<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\CreateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class CreateGameSchedule extends CreateRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // TODO: Try catch with Halt-Exception an Error-Notification

        return app(CreateGameScheduleAction::class)->execute(
            app($this->getModel()),
            GameScheduleData::create($data)
        );
    }
}
