<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications;

class AttachEntrySucceededNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        return $static
            ->title(__('filament-support::actions/attach.single.messages.attached'))
            ->success();
    }
}
