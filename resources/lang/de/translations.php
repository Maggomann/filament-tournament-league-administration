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
                    'game_schedule_id' => 'Bitte wählen Sie einen Spielplan aus.',
                    'game_day_id' => 'Bitte wählen Sie ein Spieltag aus.',
                    'home_team_id' => 'Bitte wählen Sie ein Team (Home) aus.',
                    'guest_team_id' => 'Bitte wählen Sie ein Team (Gast) aus.',
                ],
            ],
            'tabs' => [
                'game_schedule' => 'Spielplan',
                'teams' => 'Mannschaften',
                'points' => 'Punkte',
                'points_after_overtime' => 'Punkte nach Verlängerung',
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
        'game_day_started_at_pre_days' => 'Das Startdatum :value muss größer als das Enddatum der Vortage sein.',
        'game_day_date_must_be_between_game_schedule_dates' => 'Das Datum :value muss zwischen dem Start- und Enddatum des Spielplans liegen.',
        'game_date_must_be_between_game_schedule_dates' => 'Das Datum :value muss zwischen dem Start- und Enddatum des Spieltages liegen.',
        'game_day_ended_at_pre_days' => 'Das Enddatum :value muss kleiner als das Startdatum der nachfolgenden Tage sein.',
        'ended_at_must_be_greater_than_started_at' => 'Das Enddatum :value muss nach dem Startdatum liegen.',
        'started_at_must_be_smaller_than_ended_at' => 'Das Startdatum :value muss vor dem Enddatum liegen.',
    ],

];
