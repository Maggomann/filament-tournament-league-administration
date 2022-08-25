<?php

namespace Database\Seeders\FilamentTournamentLeagueAdministration;

use Illuminate\Database\Seeder;
use Database\Factories\FilamentTournamentLeagueAdministration\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

class LeaguesTableSeeder extends Seeder
{
    public function run()
    {
        Federation::get()->each(fn (Federation $federation) => LeagueFactory::new()
            ->times(2)
            ->create(['federation_id' => $federation->id])
        );
    }
}
