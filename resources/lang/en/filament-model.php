<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */

    'models' => [
        'calculation_type' => 'calculation type|calculation types',
        'federation' => 'federation|associations',
        'league' => 'league|leagues',
        'team' => 'team|teams',
        'player' => 'player|players',
        'address' => 'address|addresses',
        'game_schedule' => 'game schedule|game schedules',
        'day' => 'game day|game days',
        'game' => 'game|games',
        'total_team_point' => 'point table|point tables',
        'free_tournament' => 'free tournament|free tournaments',
        'event_location' => 'venue|venues',
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
            'calculation_type_id' => 'calculation type',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'league' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
            'federation_id' => 'Federation',
            'federation' => [
                'name' => 'Federation',
            ],
        ],
        'team' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'league_id' => 'League',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'player' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'Email address',
            'nickname' => 'Nickname',
            'id_number' => 'Badge number',
            'team_id' => 'Team',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'calculation_type' => [
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'game_schedule' => [
            'name' => 'Name',
            'game_days' => 'game days',
            'started_at' => 'Start',
            'ended_at' => 'End',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'game' => [
            'game_schedule_id' => 'Game schedule',
            'game_day_id' => 'Game day',
            'home_team_id' => 'Home team',
            'guest_team_id' => 'Guest team',
            'home_points_legs' => 'Home legs',
            'guest_points_legs' => 'Guest Legs',
            'home_points_games' => 'Home points games',
            'guest_points_games' => 'Guest points games',
            'has_an_overtime' => 'Has an overtime',
            'home_points_after_draw' => 'Home Points After Overtime',
            'guest_points_after_draw' => 'Guest points after overtime',
            'started_at' => 'Start',
            'ended_at' => 'End',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'game_day' => [
            'game_schedule_id' => 'Game schedule',
            'day' => 'Game day',
            'started_at' => 'Start',
            'ended_at' => 'End',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'tournament_league_player_roles' => [
            'title' => [
                'captain' => 'captain',
                'cocaptain' => 'co-captain',
                'player' => 'player',
            ],
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'total_team_point' => [
            'game_schedule_id' => 'Game schedule',
            'placement' => 'Place',
            'team_id' => 'Team',
            'total_number_of_encounters' => 'Number of encounters',
            'total_wins' => 'W',
            'total_defeats' => 'def',
            'total_draws' => 'dra',
            'total_victory_after_defeats' => 'VaD',
            'total_home_points_legs' => 'Home total points legs',
            'total_guest_points_legs' => 'Guest total points legs',
            'total_home_points_games' => 'Home total points games',
            'total_guest_points_games' => 'Guest total points games',
            'total_home_points_after_draw' => 'Home total points after draw',
            'total_guest_points_after_draw' => 'Guest total points after draw',
            'total_points' => 'Points',
            'legs' => 'Legs',
            'games' => 'Games',
            'points_after_draws' => 'Points. a. d.',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
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
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'tournament_league_dart_types' => [
            'title' => [
                'soft_darts' => 'Soft-Darts',
                'steel_darts' => 'Steel-Darts',
            ],
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'tournament_league_qualification_levels' => [
            'title' => [
                'open' => 'open',
                'c_league' => 'C-league',
                'up_to_b_league' => 'up to B-league',
                'until_a_league' => 'up to A-league',
                'until_bz_league' => 'up to BZ league',
                'until_bzo_league' => 'up to BZO league',
                'until_bundesliga' => 'up to Bundesliga',
            ],
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'free_tournament' => [
            'mode_id' => 'Game mode',
            'dart_type_id' => 'Dart type',
            'qualification_level_id' => 'Highest qualification level',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'maximum_number_of_participants' => 'Maximum number of participants',
            'coin_money' => 'Coin money',
            'prize_money_depending_on_placement' => 'Prize money depending on placement',
            'started_at' => 'Start',
            'ended_at' => 'End',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
            'prize_money_depending_on_placement_key_value' => [
                'key_label' => 'placement',
                'value_label' => 'price',
            ],
            'event_location' => 'venue',
        ],
        'event_location' => [
            'name' => 'Name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    'navigation_group' => [
        'federation' => [
            'name' => 'Seasons & Tournaments',
        ],
        'league' => [
            'name' => 'Seasons & Tournaments',
        ],
        'team' => [
            'name' => 'Seasons & Tournaments',
        ],
        'player' => [
            'name' => 'Seasons & Tournaments',
        ],
        'calculation_type' => [
            'name' => 'Seasons & Tournaments',
        ],
        'game_schedule' => [
            'name' => 'Seasons & Tournaments',
        ],
        'game' => [
            'name' => 'Seasons & Tournaments',
        ],
        'free_tournament' => [
            'name' => 'Seasons & Tournaments',
        ],
    ],

];
