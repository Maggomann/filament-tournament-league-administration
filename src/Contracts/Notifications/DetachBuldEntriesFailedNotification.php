<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications;

class DetachBuldEntriesFailedNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'notifications.detach_entry_failed';

        return $static
            ->title(__($translationKey))
            ->danger();
    }
}
