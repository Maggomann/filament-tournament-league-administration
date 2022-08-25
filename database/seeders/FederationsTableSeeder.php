<?php

namespace Database\Seeders\FilamentTournamentLeagueAdministration;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Database\Factories\FederationFactory;

class FederationsTableSeeder extends Seeder
{
    public function run()
    {
        FederationFactory::new()
            ->times(3)
            ->create();
    }
}
