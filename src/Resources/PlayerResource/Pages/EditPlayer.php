<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions\UpdatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // TODO: Try catch with Halt-Exception an Error-Notification

        return app(UpdatePlayerAction::class)->execute($record, PlayerData::create($data));
    }
}
