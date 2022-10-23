<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class EditGameSchedule extends EditRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // TODO: Try catch with Halt-Exception an Error-Notification

        return app(UpdateGameScheduleAction::class)->execute($record, GameScheduleData::create($data));
    }
}
