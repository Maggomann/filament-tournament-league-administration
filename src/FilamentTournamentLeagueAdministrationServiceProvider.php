<?php

namespace Maggomann\FilamentTournamentLeagueAdministration;

use Filament\PluginServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamenPublishMediaPluginAndMigrateCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentInstallWithAddressableCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Commands\FilamentTournamentLeagueAdministrationCommand;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Spatie\LaravelPackageTools\Package;

class FilamentTournamentLeagueAdministrationServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        FederationResource::class,
        FreeTournamentResource::class,
        LeagueResource::class,
        TeamResource::class,
        PlayerResource::class,
        GameScheduleResource::class,
        GameResource::class,
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
            ->hasMigrations([
                'create_filament_select_option_types_tables',
                'create_game_schedule_player_table',
                'create_game_guest_player_table',
                'create_game_home_player_table',
                'create_game_schedule_team_table',
                'create_tournament_league_calculation_types_table',
                'create_tournament_league_event_locations_table',
                'create_tournament_league_federations_table',
                'create_tournament_league_free_tournaments_table',
                'create_tournament_league_game_days_table',
                'create_tournament_league_game_encounter_guest_player_table',
                'create_tournament_league_game_encounter_home_player_table',
                'create_tournament_league_game_encounters_table',
                'create_tournament_league_game_encounter_type_table',
                'create_tournament_league_game_encounter_total_table',
                'create_tournament_league_game_schedules_table',
                'create_tournament_league_games_table',
                'create_tournament_league_leagues_table',
                'create_tournament_league_player_roles_table',
                'create_tournament_league_players_table',
                'create_tournament_league_teams_table',
                'create_tournament_league_total_team_points_table',
            ])
            ->hasCommands([
                FilamentTournamenPublishMediaPluginAndMigrateCommand::class,
                FilamentTournamentLeagueAdministrationCommand::class,
                FilamentTournamentInstallWithAddressableCommand::class,
            ]);
    }
}
