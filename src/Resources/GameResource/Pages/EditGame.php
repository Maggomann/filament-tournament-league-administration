<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateGameAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;
use Throwable;

class EditGame extends EditRecord
{
    protected static string $resource = GameResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            /** @var Game $record */
            return app(UpdateGameAction::class)->execute($record, GameData::create($data));
        } catch (Throwable $e) {
            EditEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
