<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attribute
    |--------------------------------------------------------------------------
    */

    'forms' => [
        'components' => [
            'select' => [
                'placeholder' => [
                    'address_category_id' => 'Bitte wählen Sie ein Adresstypen aus.',
                    'address_country_code' => 'Bitte wählen Sie ein Land aus.',
                    'address_gender_id' => 'Bitte wählen Sie eine Anrede aus.',
                    'calculation_type_id' => 'Bitte wählen Sie ein Kalkulationstyp aus.',
                    'federation_id' => 'Bitte wählen Sie ein Verband aus.',
                    'game_days' => 'Bitte wählen Sie die Anzahl der Spieltage aus.',
                    'league_id' => 'Bitte wählen Sie eine Liga aus.',
                    'player_id' => 'Bitte wählen Sie einen Spieler aus.',
                    'team_id' => 'Bitte wählen Sie ein Team aus.',
                ],
            ],
        ],
    ],
    'notifications' => [
        'attach_entry_failed' => 'Es ist ein Fehler beim Zuweisen der Datensätze aufgetreten',
        'delete_entry_failed' => 'Es ist ein Fehler beim Löschen des Datensetzes aufgetreten',
        'detach_entry_failed' => 'Es ist ein Fehler beim Trennen der Datensätze aufgetreten',
        'edit_entry_failed' => 'Es ist ein Fehler beim Bearbeiten des Datensetzes aufgetreten',

    ],
    'rules' => [
        'unique_game_day' => 'Der :value. Tag ist bereits vorhanden.',
    ],
];
