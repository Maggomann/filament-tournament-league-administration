<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Player\Actions\CreatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreatePlayerAction::class)->execute(
            app($this->getModel()),
            PlayerData::create($data)
        );
    }
}
