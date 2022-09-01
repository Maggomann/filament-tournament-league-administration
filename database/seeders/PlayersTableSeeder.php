<?php

namespace Database\Seeders;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;

class PlayersTableSeeder extends Seeder
{
    public function run(): void
    {
        Team::get()->each(fn (Team $team) => PlayerFactory::new()
            ->times(3)
            ->create(['team_id' => $team->id])
        );
    }
}
