<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications;

class DeleteEntryFailedNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'notifications.delete_entry_failed';

        return $static
            ->title(__($translationKey))
            ->danger();
    }
}
