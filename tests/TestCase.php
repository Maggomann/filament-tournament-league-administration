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
use Livewire\LivewireServiceProvider;
use Maggomann\Addressable\AddressableServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\FilamentTournamentLeagueAdministrationServiceProvider;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Database\Factories\UserFactory;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

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
            AddressableServiceProvider::class,
            FilamentTournamentLeagueAdministrationServiceProvider::class,
            MediaLibraryServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            __DIR__.'/../vendor/maggomann/addressable/database/migrations'
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
