<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\PlayerRole;

class PlayerRolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $playerRoles = [
            [
                'title_translation_key' => 'tournament_league_player_roles.title.captain',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_player_roles.title.cocaptain',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title_translation_key' => 'tournament_league_player_roles.title.player',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        PlayerRole::insert($playerRoles);
    }
}
