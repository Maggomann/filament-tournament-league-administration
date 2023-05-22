<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounterType;

class GameEncounterTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $gameEncounterTypes = [
            [
                'title_translation_key' => 'tournament_league_game_encounter_type.title.one_versus_one',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_game_encounter_type.title.two_versus_two',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        GameEncounterType::insert($gameEncounterTypes);
    }
}
