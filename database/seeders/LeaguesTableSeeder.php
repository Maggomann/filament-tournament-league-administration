<?php

namespace Database\Seeders;

use Database\Factories\LeagueFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

class LeaguesTableSeeder extends Seeder
{
    public function run(): void
    {
        Federation::get()->each(fn (Federation $federation) => LeagueFactory::new()
            ->times(2)
            ->create(['federation_id' => $federation->id])
        );
    }
}
