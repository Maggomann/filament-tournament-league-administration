<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */

    'models' => [
        'calculation_type' => 'Kalkulationstyp|Kalkulationstypen',
        'federation' => 'Verband|Verbände',
        'league' => 'Liga|Ligen',
        'team' => 'Team|Teams',
        'player' => 'Spieler|Spieler',
        'address' => 'Adresse|Adressen',
        'game_schedule' => 'Spielplan|Spielpläne',
        'day' => 'Spieltag|Spieltage',
        'game' => 'Spiel|Spiele',
        'total_team_point' => 'Punktetabelle|Punktetabellen',
        'free_tournament' => 'Freies Turnier|Freie Tuniere',
    ],

    /*
    |--------------------------------------------------------------------------
    | Attribute
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'federation' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'calculation_type_id' => 'Kalkulationstyp',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'league' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
            'federation_id' => 'Verband',
            'federation' => [
                'name' => 'Verband',
            ],
        ],
        'team' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'league_id' => 'Liga',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'player' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'E-Mail-Adresse',
            'team_id' => 'Team',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'calculation_type' => [
            'name' => 'Name',
            'description' => 'Beschreibung',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'game_schedule' => [
            'name' => 'Name',
            'game_days' => 'Spieltage',
            'started_at' => 'Beginn',
            'ended_at' => 'Ende',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'game' => [
            'game_schedule_id' => 'Spielplan',
            'game_day_id' => 'Spieltag',
            'home_team_id' => 'Heim Team',
            'guest_team_id' => 'Gast Team',
            'home_points_legs' => 'Heim Legs',
            'guest_points_legs' => 'Gast Legs',
            'home_points_games' => 'Heim-Punkte Games',
            'guest_points_games' => 'Gast-Punkte Games',
            'has_an_overtime' => 'Hat eine Verlängerung',
            'home_points_after_draw' => 'Heim-Punkte nach Verlängerung',
            'guest_points_after_draw' => 'Gast-Punkte nach Verlängerung',
            'started_at' => 'Beginn',
            'ended_at' => 'Ende',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'game_day' => [
            'game_schedule_id' => 'Spielplan',
            'day' => 'Spieltag',
            'started_at' => 'Beginn',
            'ended_at' => 'Ende',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'total_team_point' => [
            'game_schedule_id' => 'Spielplan',
            'team_id' => 'Team',
            'total_number_of_encounters' => 'Anz. Beg.',
            'total_wins' => 'S',
            'total_defeats' => 'N',
            'total_draws' => 'U',
            'total_victory_after_defeats' => 'SnU',
            'total_home_points_legs' => 'Heim Gesamtpunkte Legs',
            'total_guest_points_legs' => 'Gast Gesamtpunkte Legs',
            'total_home_points_games' => 'Heim Gesamtpunkte Spiele',
            'total_guest_points_games' => 'Gast Gesamtpunkte Spiele',
            'total_home_points_after_draw' => 'Heim Gesamtpunkte nach Unentschieden',
            'total_guest_points_after_draw' => 'Gast Gesamtpunkte nach Unentschieden',
            'total_points' => 'Punkte',
            'legs' => 'Legs',
            'games' => 'Spiele',
            'points_after_draws' => 'Pkt. n. U.',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert',
            'deleted_at' => 'Gelöscht am',
        ],
        'tournament_league_modes' => [
            'title' => [
                '301_so' => '301-So',
                '301_mo' => '301-Mo',
                '301_do' => '301-Do',
                '501_so' => '501-So',
                '501_mo' => '501-Mo',
                '501_do' => '501-Do',
                'cricket' => 'Cricket',
                'splitscore' => 'Splitscore',
                'shanghai' => 'Shanghai',
            ],
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'tournament_league_dart_types' => [
            'title' => [
                'soft_darts' => 'Soft-Darts',
                'steel_darts' => 'Steel-Darts',
            ],
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'tournament_league_qualification_levels' => [
            'title' => [
                'open' => 'offen',
                'c_league' => 'C-Liga',
                'up_to_b_league' => 'bis B-Liga',
                'until_a_league' => 'bis A-Liga',
                'until_bz_league' => 'bis BZ-Liga',
                'until_bzo_league' => 'bis BZO-Liga',
                'until_bundesliga' => 'bis Bundesliga',
            ],
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'free_tournament' => [
            'mode_id' => 'Spielmodus',
            'dart_type_id' => 'Dart-Typ',
            'qualification_level_id' => 'Höchstes Qualifikationsniveau',
            'name' => 'Name',
            'slug' => 'Slug',
            'maximum_number_of_participants' => 'Maximale Anzahl von Teilnehmern',
            'coin_money' => 'Münzgeld',
            'prize_money_depending_on_placement' => 'Preisgeld je nach Platzierung',
            'started_at' => 'Beginn',
            'ended_at' => 'Ende',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    'navigation_group' => [
        'federation' => [
            'name' => 'Saisons & Turniere',
        ],
        'league' => [
            'name' => 'Saisons & Turniere',
        ],
        'team' => [
            'name' => 'Saisons & Turniere',
        ],
        'player' => [
            'name' => 'Saisons & Turniere',
        ],
        'calculation_type' => [
            'name' => 'Saisons & Turniere',
        ],
        'game_schedule' => [
            'name' => 'Saisons & Turniere',
        ],
        'game' => [
            'name' => 'Saisons & Turniere',
        ],
        'free_tournament' => [
            'name' => 'Saisons & Turniere',
        ],
    ],

];
