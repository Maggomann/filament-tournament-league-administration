<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications;

class EditEntryFailedNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'notifications.edit_entry_failed';

        return $static
            ->title(__($translationKey))
            ->danger();
    }
}
