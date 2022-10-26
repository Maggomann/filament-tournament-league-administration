<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications;

class AttachEntryFailedNotification extends Notification
{
    public static function make(?string $id = null): static
    {
        $static = parent::make();

        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'notifications.attach_entry_failed';

        return $static
            ->title(__($translationKey))
            ->danger();
    }
}
