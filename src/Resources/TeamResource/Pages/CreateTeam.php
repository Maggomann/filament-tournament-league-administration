<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Team\Actions\CreateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateTeamAction::class)->execute(
            app($this->getModel()),
            TeamData::create($data)
        );
    }
}
