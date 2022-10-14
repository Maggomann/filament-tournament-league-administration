<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\FilamentTournamentLeagueAdministrationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Support\Str;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Maggomann\\FilamentTournamentLeagueAdministration\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            FilamentTournamentLeagueAdministrationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('app.key', Str::random(32));

        $this->dropAndCreateDatabase();

        $migration = include __DIR__.'/../database/migrations/create_filament_tournament_league_administration_tables.php';
        $migration->up();

    }

    private function dropAndCreateDatabase(): void
    {
        if (app('env') !== 'testing') {
            return;
        }

        $database = 'testing';

        config()->set('database.default', 'mysql');
        config()->set('database.connections.mysql.database', $database);
        config()->set('database.connections.mysql.username', 'root');

        Schema::getConnection()->getDoctrineSchemaManager()->dropDatabase("`{$database}`");

        Schema::getConnection()->getDoctrineSchemaManager()->createDatabase("`{$database}`");

        DB::reconnect('mysql');
    }
}
