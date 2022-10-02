<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications;

class DetachBuldEntriesSucceededNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        return $static
            ->title(__('filament-support::actions/detach.multiple.messages.detached'))
            ->success();
    }
}
