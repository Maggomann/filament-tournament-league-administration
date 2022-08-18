<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Commands;

use Illuminate\Console\Command;

class FilamentTournamentLeagueAdministrationCommand extends Command
{
    public $signature = 'filament-tournament-league-administration';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
