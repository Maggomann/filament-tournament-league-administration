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
            'period_start' => 'Beginn',
            'period_end' => 'Ende',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
            'deleted_at' => 'Gelöscht am',
        ],
        'game_day' => [
            'game_schedule_id' => 'Spielplan',
            'day' => 'Spieltag',
            'started_at' => 'Beginn',
            'end' => 'Ende',
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
    ],

];
