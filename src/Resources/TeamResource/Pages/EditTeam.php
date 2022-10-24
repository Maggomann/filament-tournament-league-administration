<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions\UpdateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Throwable;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(UpdateTeamAction::class)->execute($record, TeamData::create($data));
        } catch (Throwable $e) {
            EditEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
