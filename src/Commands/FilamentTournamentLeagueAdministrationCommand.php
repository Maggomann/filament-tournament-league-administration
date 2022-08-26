<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;

class FilamentTournamentLeagueAdministrationCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:install';

    protected $description = 'Install all of the filament-tournament-league-administration resources';

    public function handle(): int
    {
        $this->comment('Publishing tournament-league-administration Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-config']);

        $this->comment('Publishing Filament tournament-league-administration Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-migrations']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-migrations']);

        $this->comment('Publishing Filament tournament-league-administration languages...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-translations']);

        $this->info('Filament filament-tournament-league-administration was installed successfully.');

        return self::SUCCESS;
    }



}
