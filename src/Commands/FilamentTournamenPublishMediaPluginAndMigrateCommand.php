<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class FilamentTournamenPublishMediaPluginAndMigrateCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:publish-media-plugin-and-migrate';

    protected $description = 'Publish the migration to create the media table';

    public function handle(): int
    {
        $this->comment('Publishing filament/spatie-laravel-media-library-plugin data and migrate');

        if ($this->migrationExist()) {
            $this->callSilent('vendor:publish', [
                '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            ]);

            $this->info('Migration file already exists - nothing to migrate');

            return self::SUCCESS;
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            '--tag' => 'migrations',
        ]);

        Artisan::call('migrate');

        return self::SUCCESS;
    }

    protected function migrationExist(): bool
    {
        if (class_exists('CreateMediaTable')) {
            return true;
        }

        return ! blank(
            collect(scandir(database_path('migrations/')))
                ->first(fn ($fileName) => Str::of($fileName)->contains('_create_media_table'))
            );
    }

    protected function migrationDoesntExist(): bool
    {
        return ! $this->migrationExist();
    }
}
