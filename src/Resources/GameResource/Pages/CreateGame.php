<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateOrCreateGameAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;
use Throwable;

class CreateGame extends CreateRecord
{
    protected static string $resource = GameResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(UpdateOrCreateGameAction::class)->execute(GameData::create($data));
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
