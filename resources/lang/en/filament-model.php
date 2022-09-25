<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */

    'models' => [
        'calculation_type' => 'Calculation type|Calculation types',
        'federation' => 'Association|Associations',
        'league' => 'League|Leagues',
        'team' => 'Team|Teams',
        'player' => 'Player|Players',
        'game_schedule' => 'Game schedole|Game schedoles',
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
            'calculation_type_id' => 'Calculation type',
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
            'federation_id' => 'Association',
            'federation' => [
                'name' => 'Association',
            ],
        ],
        'team' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'league_id' => 'Liga',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'player' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'E-Mail',
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
            'period_start' => 'Begin',
            'period_end' => 'Ende',
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
    ],
];
