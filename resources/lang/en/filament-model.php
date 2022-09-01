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
            'federation' => [
                'name' => 'Association',
            ],
        ],
        'calculation_type' => [
            'name' => 'Name',
            'description' => 'Description',
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
        'calculation_type' => [
            'name' => 'Seasons & Tournaments',
        ],
    ],
];