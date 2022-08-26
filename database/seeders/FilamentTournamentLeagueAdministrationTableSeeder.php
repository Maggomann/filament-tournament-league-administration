<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FilamentTournamentLeagueAdministrationTableSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            FederationsTableSeeder::class,
            LeaguesTableSeeder::class,
        ]);
    }
}
