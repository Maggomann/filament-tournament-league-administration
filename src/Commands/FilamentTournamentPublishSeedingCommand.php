<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;

class FilamentTournamentPublishSeedingCommand extends Command
{
    public $signature = 'filament-tournament-league-administration:publish-seeding';

    protected $description = 'Publish all of the filament-tournament-league-administration seeding files';

    public function handle(): int
    {
        $this->comment('Publishing Filament tournament-league-administration seeders and factroies...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-seeders']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-seeders']);
        $this->callSilent('vendor:publish', ['--tag' => 'filament-tournament-league-administration-factories']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-factories']);

        $this->info('Filament filament-tournament-league-administration seeding files was published successfully.');

        return self::SUCCESS;
    }



}
