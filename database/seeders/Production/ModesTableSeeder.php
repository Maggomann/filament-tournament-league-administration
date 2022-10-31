<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Mode;

class ModesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $modes = [
            [
                'title_translation_key' => 'tournament_league_modes.title.soft_darts',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.steel_darts',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Mode::insert($modes);
    }
}
