<?php

namespace Database\Seeders;

use Database\Factories\AddressFactory;
use Database\Factories\FreeTournamentFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;

class FreeTournamentsTableSeeder extends Seeder
{
    public function run(): void
    {
        FreeTournamentFactory::new()
            ->times(3)
            ->create();

        FreeTournament::get()->each(fn (FreeTournament $freeTournament) => AddressFactory::new()
            ->create([
                'addressable_id' => $freeTournament->id,
                'addressable_type' => $freeTournament->getMorphClass(),
            ])
        );
    }
}
