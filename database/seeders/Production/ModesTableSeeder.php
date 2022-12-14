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
                'title_translation_key' => 'tournament_league_modes.title.301_so',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.301_mo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.301_do',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.501_so',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.501_mo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.501_do',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.cricket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.splitscore',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_modes.title.shanghai',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Mode::insert($modes);
    }
}
