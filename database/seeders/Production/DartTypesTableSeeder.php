<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\DartType;

class DartTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $dartTypes = [
            [
                'title_translation_key' => 'tournament_league_dart_types.title.301_so',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.301_mo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.301_do',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.501_so',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.501_mo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.501_do',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.cricket',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.splitscore',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.shanghai',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DartType::insert($dartTypes);
    }
}
