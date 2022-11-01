<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\Actions\UpdateOrCreateFreeTournamentAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO\FreeTournamentData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use Throwable;

class CreateFreeTournament extends CreateRecord
{
    protected static string $resource = FreeTournamentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(UpdateOrCreateFreeTournamentAction::class)->execute(FreeTournamentData::create($data));
        } catch (Throwable $e) {
            CreatedEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
