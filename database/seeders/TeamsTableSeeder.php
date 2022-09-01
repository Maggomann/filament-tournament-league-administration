<?php

namespace Database\Seeders;

use Database\Factories\TeamFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;

class TeamsTableSeeder extends Seeder
{
    public function run(): void
    {
        League::get()->each(fn (League $league) => TeamFactory::new()
            ->times(5)
            ->create(['league_id' => $league->id])
        );
    }
}
