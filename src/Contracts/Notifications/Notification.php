<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Notifications;

use Filament\Notifications\Notification as FilamentNotification;

class Notification extends FilamentNotification
{
    protected static ?string $translateablePackageKey = 'filament-tournament-league-administration::';
}
