<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateGameAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;

class EditGame extends EditRecord
{
    protected static string $resource = GameResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // TODO: Try catch with Halt-Exception an Error-Notification

        return app(UpdateGameAction::class)->execute($record, GameData::create($data));
    }
}
