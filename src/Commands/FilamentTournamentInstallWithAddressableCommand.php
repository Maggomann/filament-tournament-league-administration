<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FilamentTournamentInstallWithAddressableCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:install-with-addressable';

    protected $description = 'Publish all of the filament-tournament-league-administration including addressable data';

    public function handle(): int
    {
        Artisan::call('laravel-addressable:install');
        Artisan::call('laravel-addressable:install-filament');
        Artisan::call('filament-tournament-league-administration:install');

        return self::SUCCESS;
    }
}
