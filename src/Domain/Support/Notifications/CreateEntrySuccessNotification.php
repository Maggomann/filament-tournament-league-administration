<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications;

class CreateEntrySuccessNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        return $static
            ->title(__('filament::resources/pages/create-record.messages.created'))
            ->success();
    }
}
