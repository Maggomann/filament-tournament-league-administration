<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\Actions\UpdateOrCreateFreeTournamentAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO\FreeTournamentData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use Throwable;

class EditFreeTournament extends EditRecord
{
    protected static string $resource = FreeTournamentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            /** @var FreeTournament $record */
            return app(UpdateOrCreateFreeTournamentAction::class)->execute(
                FreeTournamentData::from($data),
                $record
            );
        } catch (Throwable $e) {
            EditEntryFailedNotification::make()->send();

            throw new Halt($e->getMessage());
        }
    }
}
