<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions\CreatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Throwable;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CreatePlayerAction::class)->execute(
                app($this->getModel()),
                PlayerData::create($data)
            );
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
