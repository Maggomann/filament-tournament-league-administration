<?php

namespace Maggomann\FilamentTournamentLeagueAdministration;

use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentLeagueAdministrationCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTournamentLeagueAdministrationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-tournament-league-administration')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_filament-tournament-league-administration_table')
            ->hasCommand(FilamentTournamentLeagueAdministrationCommand::class);
    }
}
