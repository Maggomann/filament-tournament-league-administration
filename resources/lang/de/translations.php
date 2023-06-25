<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attribute
    |--------------------------------------------------------------------------
    */

    'forms' => [
        'components' => [
            'buttons' => [
                'labels' => [
                    'link_all_guest_players_from_the_linked_guest_team' => 'Alle Gast-Spieler aus dem verknüpften Gast-Team verknüpfen',
                    'link_all_home_players_from_the_linked_home_team' => 'Alle Heim-Spieler aus dem verknüpften Heim-Team verknüpfen',
                    'link_all_players_from_the_linked_teams' => 'Alle Spieler aus den verknüpften Teams verknüpfen',
                    'link_all_teams_in_the_league' => 'Alle Teams aus der Liga verknüpfen',
                    'recalculation_of_the_game_plan_points' => 'Neuberechnung der Spielplanpunkte',
                ],
            ],
            'select' => [
                'placeholder' => [
                    'address_category_id' => 'Bitte wählen Sie ein Adresstypen aus.',
                    'address_country_code' => 'Bitte wählen Sie ein Land aus.',
                    'address_gender_id' => 'Bitte wählen Sie eine Anrede aus.',
                    'calculation_type_id' => 'Bitte wählen Sie ein Kalkulationstyp aus.',
                    'federation_id' => 'Bitte wählen Sie ein Verband aus.',
                    'game_days' => 'Bitte wählen Sie die Anzahl der Spieltage aus.',
                    'home_player_id' => 'Bitte wählen Sie einen Spieler (Heim) aus.',
                    'guest_player_id' => 'Bitte wählen Sie einen Spieler (Gast) aus.',
                    'league_id' => 'Bitte wählen Sie eine Liga aus.',
                    'player_id' => 'Bitte wählen Sie einen Spieler aus.',
                    'team_id' => 'Bitte wählen Sie ein Team aus.',
                    'game_schedule_id' => 'Bitte wählen Sie einen Spielplan aus.',
                    'game_encounter_type_id' => 'Bitte wählen Sie einen Spielbegegnungstyp aus.',
                    'game_day_id' => 'Bitte wählen Sie ein Spieltag aus.',
                    'home_team_id' => 'Bitte wählen Sie ein Team (Home) aus.',
                    'guest_team_id' => 'Bitte wählen Sie ein Team (Gast) aus.',
                    'mode_id' => 'Bitte wählen Sie ein Spielmodus aus.',
                    'type_id' => 'Bitte wählen Sie ein Dart-Typ aus.',
                    'qualification_level_id' => 'Bitte wählen Sie ein Qualifikationsniveau aus.',
                    'event_location' => 'Bitte wählen Sie ein Veranstaltungsort aus.',
                ],
            ],
            'tabs' => [
                'game' => 'Spiel',
                'game_schedule' => 'Spielplan',
                'guest_players' => 'Gast-Spieler',
                'home_players' => 'Heim-Spieler',
                'players' => 'Spieler',
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
        'create_entry_failed' => 'Es ist ein Fehler beim Speichern des Datensetzes aufgetreten',
    ],
    'rules' => [
        'unique_game_day' => 'Der :value. Tag ist bereits vorhanden.',
        'game_day_started_at_pre_days' => 'Das Startdatum :value muss größer als das Enddatum der Vortage sein.',
        'game_day_date_must_be_between_game_schedule_dates' => 'Das Datum :value muss zwischen dem Start- und Enddatum des Spielplans liegen.',
        'game_date_must_be_between_game_schedule_dates' => 'Das Datum :value muss zwischen dem Start- und Enddatum des Spieltages liegen.',
        'game_day_ended_at_pre_days' => 'Das Enddatum :value muss kleiner als das Startdatum der nachfolgenden Tage sein.',
        'ended_at_must_be_greater_than_started_at' => 'Das Enddatum :value muss nach dem Startdatum liegen.',
        'started_at_must_be_smaller_than_ended_at' => 'Das Startdatum :value muss vor dem Enddatum liegen.',
        'game_schedule_started_at_must_be_outside_from_the_days_time_periods' => 'Es liegt eine Überschneidung mit den Zeiträumen der zugewiesenen Tage vor. Das Startdatum :value muss außerhalb der Zeiträume der zugewiesenen Tage liegen.',
        'game_schedule_started_at_must_be_outside_from_the_days_time_periods' => 'Es liegt eine Überschneidung mit den Zeiträumen der zugewiesenen Tage vor. Das Enddatum :value muss außerhalb der Zeiträume der zugewiesenen Tage liegen.',
    ],

];
