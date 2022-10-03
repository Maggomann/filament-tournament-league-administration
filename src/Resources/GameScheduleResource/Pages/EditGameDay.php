<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions\UpdateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;

class EditGameDay extends EditRecord
{
    protected static string $resource = GameScheduleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(UpdateGameScheduleAction::class)->execute($record, GameScheduleData::create($data));
    }
}