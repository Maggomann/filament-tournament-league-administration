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
    ],

];
