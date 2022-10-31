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
                'title_translation_key' => 'tournament_league_dart_types.title.soft_darts',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_dart_types.title.steel_darts',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DartType::insert($dartTypes);
    }
}
