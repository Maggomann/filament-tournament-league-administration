<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;

class FilamentTournamentLeagueAdministrationCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:install';

    protected $description = 'Publish all of the filament-tournament-league-administration files';

    public function handle(): int
    {
        $this->comment('Publishing tournament-league-administration Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-config']);

        $this->comment('Publishing Filament tournament-league-administration Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-migrations']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-migrations']);

        $this->comment('Publishing Filament tournament-league-administration languages...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-translations']);

        $this->comment('Publishing Filament tournament-league-administration seeders and factories...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-seeders']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-seeders']);
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-factories']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-factories']);

        $this->info('Filament filament-tournament-league-administration was installed successfully.');

        return self::SUCCESS;
    }
}
