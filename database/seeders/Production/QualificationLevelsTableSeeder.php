<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\QualificationLevel;

class QualificationLevelsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $qualificationLevels = [
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.open',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.c_league',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.up_to_b_league',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.until_a_league',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.until_bz_league',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.until_bzo_league',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_qualification_levels.title.until_bundesliga',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        QualificationLevel::insert($qualificationLevels);
    }
}
