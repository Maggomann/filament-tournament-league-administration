<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FilamentTournamentInstallAndSeedingCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:install-and-seeding';

    protected $description = 'Publish all of the filament-tournament-league-administration seeding files and install this';

    public function handle(): int
    {
        Artisan::call('laravel-addressable:install');
        Artisan::call('laravel-addressable:install-filament');
        Artisan::call('filament-tournament-league-administration:install');
        Artisan::call('filament-tournament-league-administration:publish-seeding');

        return self::SUCCESS;
    }
}
