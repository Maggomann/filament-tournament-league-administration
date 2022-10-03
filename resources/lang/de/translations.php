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
        'game_day_start_must_be_smaller_than_end_date' => 'Das Startdatum :value muss vor dem Enddatum liegen.',
        'game_day_start_pre_days' => 'Das Startdatum :value muss größer als das Enddatum der Vortage sein.',
        'game_day_start_must_be_between_game_schedule_dates' => 'Das Startdatum :value muss zwischen dem Start- und Enddatum des Spielplans liegen.',
        'game_day_end_must_be_greater_than_start_date' => 'Das Enddatum :value muss nach dem Startdatum liegen.',
        'game_day_end_pre_days' => 'Das Enddatum :value muss kleiner als das Startdatum der nachfolgenden Tage sein.',
        'period_start_game_schedule_must_be_smaller_than_period_end' => 'Das Startdatum :value muss vor dem Enddatum liegen.',
        'period_end_game_schedule_must_be_greather_than_period_start' => 'Das Enddatum :value muss nach dem Startdatum liegen.',
    ],
];
