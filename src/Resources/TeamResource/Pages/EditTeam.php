<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Team\Actions\UpdateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(UpdateTeamAction::class)->execute($record, TeamData::create($data));
    }
}
