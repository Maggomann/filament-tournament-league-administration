<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions\UpdateOrCreateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Throwable;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(UpdateOrCreateTeamAction::class)->execute(TeamData::create($data), $record);
        } catch (Throwable $e) {
            EditEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
