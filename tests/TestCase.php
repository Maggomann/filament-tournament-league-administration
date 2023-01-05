<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\SpatieLaravelSettingsPluginServiceProvider;
use Filament\SpatieLaravelTranslatablePluginServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\FilamentTournamentLeagueAdministrationServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Database\Factories\UserFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Models\User;
use Maggomann\LaravelAddressable\LaravelAddressableServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

abstract class TestCase extends BaseTestCase
{
    // use LazilyRefreshDatabase;
    // use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(UserFactory::new()->create());
    }

    protected function getPackageProviders($app): array
    {
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SpatieLaravelSettingsPluginServiceProvider::class,
            SpatieLaravelTranslatablePluginServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            LaravelDataServiceProvider::class,
            LaravelAddressableServiceProvider::class,
            FilamentTournamentLeagueAdministrationServiceProvider::class,
            MediaLibraryServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            __DIR__.'/../vendor/maggomann/laravel-addressable/database/migrations'
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        // $app['config']->set('auth.providers.users.model', User::class);
        // $app['config']->set('view.paths', array_merge(
        //     $app['config']->get('view.paths'),
        //     [__DIR__ . '/../resources/views'],
        // ));

        // $app['config']->set('database.default', 'sqlite');
        // $app['config']->set('database.connections.sqlite', [
        //     'driver' => 'sqlite',
        //     'prefix' => '',
        //     'database' => ':memory:',
        // ]);
    }
}
