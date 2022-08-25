<?php

namespace Database\Seeders\FilamentTournamentLeagueAdministration;

use Illuminate\Database\Seeder;
use Database\Factories\FilamentTournamentLeagueAdministration\FederationFactory;

class FederationsTableSeeder extends Seeder
{
    public function run()
    {
        FederationFactory::new()
            ->times(3)
            ->create();
    }
}
