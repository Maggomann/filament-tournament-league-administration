<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Player\Actions\UpdatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(UpdatePlayerAction::class)->execute($record, PlayerData::create($data));
    }
}
