<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions\UpdateOrCreatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Throwable;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(UpdateOrCreatePlayerAction::class)->execute(PlayerData::from($data), $record);
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
