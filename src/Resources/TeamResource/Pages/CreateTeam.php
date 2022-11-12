<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions\UpdateOrCreateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Throwable;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(UpdateOrCreateTeamAction::class)->execute(TeamData::from($data));
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
