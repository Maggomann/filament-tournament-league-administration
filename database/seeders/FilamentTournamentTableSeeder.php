<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FilamentTournamentTableSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            FederationsTableSeeder::class,
            LeaguesTableSeeder::class,
            TeamsTableSeeder::class,
            PlayersTableSeeder::class,
        ]);
    }
}
