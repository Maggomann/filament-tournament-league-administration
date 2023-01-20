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
                    'link_all_players_from_the_linked_teams' => 'Link all players from the linked teams',
                    'link_all_teams_in_the_league' => 'Link all teams from the league',
                    'recalculation_of_the_game_plan_points' => 'Recalculation of the game plan points',
                ],
            ],
            'select' => [
                'placeholder' => [
                    'address_category_id' => 'Please select an address category.',
                    'address_country_code' => 'Please select a country',
                    'address_gender_id' => 'Please select a form of address',
                    'calculation_type_id' => 'Please select a calculation type.',
                    'federation_id' => 'Please select a federation',
                    'game_days' => 'Please select the number of game days',
                    'league_id' => 'Please select a league',
                    'player_id' => 'Please select a player.',
                    'team_id' => 'Please select a team.',
                    'game_schedule_id' => 'Please select a game schedule.',
                    'game_day_id' => 'Please select a game day.',
                    'home_team_id' => 'Please select a team (home)',
                    'guest_team_id' => 'Please select a team (guest).',
                    'mode_id' => 'Please select a game mode',
                    'type_id' => 'Please select a dart type.',
                    'qualification_level_id' => 'Please select a qualification level.',
                    'event_location' => 'Please select a venue.',
                ],
            ],
            'tabs' => [
                'game_schedule' => 'Game schedule',
                'teams' => 'Teams',
                'points' => 'Points',
                'points_after_overtime' => 'Points after overtime',
            ],
        ],
    ],
    'notifications' => [
        'attach_entry_failed' => 'An error occurred while assigning the records',
        'delete_entry_failed' => 'An error occurred while deleting the dataset',
        'detach_entry_failed' => 'An error occurred while detaching the records',
        'edit_entry_failed' => 'An error occurred while editing the dataset',
        'create_entry_failed' => 'An error occurred while saving the dataset',
    ],
    'rules' => [
        'unique_game_day' => 'The :value. Day already exists.',
        'game_day_started_at_pre_days' => 'The :value start date must be greater than the end date of the previous days.',
        'game_day_date_must_be_between_game_schedule_dates' => 'The date :value must be between the start and end dates of the schedule.',
        'game_date_must_be_between_game_schedule_dates' => 'The date :value must be between the start and end dates of the game day.',
        'game_day_ended_at_pre_days' => 'The ending date :value must be less than the starting date of the following days.',
        'ended_at_must_be_greater_than_started_at' => 'The ending date :value must be after the starting date.',
        'started_at_must_be_smaller_than_ended_at' => 'The start date :value must be before the end date.',
        'game_schedule_started_at_must_be_outside_from_the_days_time_periods' => 'There is an overlap with the periods of the assigned days. The start date :value must be outside the periods of the assigned days.',
        'game_schedule_started_at_must_be_outside_from_the_days_time_periods' => 'There is an overlap with the time periods of the assigned days. The end date :value must be outside the periods of the assigned days',
    ],

];
