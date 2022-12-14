<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateOrCreateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Throwable;

class CreateGameSchedule extends CreateRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(UpdateOrCreateGameScheduleAction::class)->execute(GameScheduleData::create($data));
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
