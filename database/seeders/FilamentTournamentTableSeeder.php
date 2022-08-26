<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FilamentTournamentTableSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            FederationsTableSeeder::class,
            LeaguesTableSeeder::class,
        ]);
    }
}
