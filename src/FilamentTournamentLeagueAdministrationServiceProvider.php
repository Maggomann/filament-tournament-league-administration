<?php

namespace Maggomann\FilamentTournamentLeagueAdministration;

use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentLeagueAdministrationCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTournamentLeagueAdministrationServiceProvider extends PackageServiceProvider
{
    protected array $resources = [
        FederationResource::class,
        LeagueResource::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tournament-league-administration')
            ->hasConfigFile()
            ->hasMigration('create_filament_tournament_league_administration_tables')
            ->hasCommand(FilamentTournamentLeagueAdministrationCommand::class);
    }
}
