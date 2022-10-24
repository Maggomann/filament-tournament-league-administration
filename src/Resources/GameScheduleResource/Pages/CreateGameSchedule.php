<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\CreateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Throwable;

class CreateGameSchedule extends CreateRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CreateGameScheduleAction::class)->execute(
                app($this->getModel()),
                GameScheduleData::create($data)
            );
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
