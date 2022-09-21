<?php

namespace Maggomann\FilamentTournamentLeagueAdministration;

use Filament\PluginServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentLeagueAdministrationCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentPublishSeedingCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Spatie\LaravelPackageTools\Package;

class FilamentTournamentLeagueAdministrationServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        FederationResource::class,
        LeagueResource::class,
        TeamResource::class,
        PlayerResource::class,
        GameScheduleResource::class,
    ];

    protected function getResources(): array
    {
        return array_merge($this->resources, (array) config('filament-tournament-league-administration.resources', []));
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tournament-league-administration')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_filament_tournament_league_administration_tables')
            ->hasCommands([
                FilamentTournamentLeagueAdministrationCommand::class,
                FilamentTournamentPublishSeedingCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->publishes([
            $this->package->basePath('/../database/seeders') => database_path('seeders/'),
        ], "{$this->package->shortName()}-seeders");

        $this->publishes([
            $this->package->basePath('/../database/factories') => database_path('factories/'),
        ], "{$this->package->shortName()}-factories");

        parent::packageBooted();
    }
}
