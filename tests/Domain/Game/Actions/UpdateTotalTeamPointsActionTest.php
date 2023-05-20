<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Database\Factories\TotalTeamPointFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateTotalTeamPointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

dataset('entriesUpdateTotalTeamPointsAction', function () {
    yield 'draw calculation_type_id one' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'outward_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),
            ],
            'home_assert_database_has' => [
                'total_points' => 2,
            ],
            'guest_assert_database_has' => [
                'total_points' => 2,
            ],
        ]),
    ];
    yield 'home win by calculation_type_id one' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'outward_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 25,
                'guest_points_legs' => 50,
                'home_points_games' => 25,
                'guest_points_games' => 50,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),

            ],
            'home_assert_database_has' => [
                'total_points' => 4,
            ],
            'guest_assert_database_has' => [
                'total_points' => 0,
            ],
        ]),
    ];
    yield 'guest win by calculation_type_id one' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'outward_game' => [
                'home_points_legs' => 25,
                'guest_points_legs' => 50,
                'home_points_games' => 25,
                'guest_points_games' => 50,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),
            ],
            'home_assert_database_has' => [
                'total_points' => 0,
            ],
            'guest_assert_database_has' => [
                'total_points' => 4,
            ],
        ]),
    ];
    yield 'draw calculation_type_id two' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'outward_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),
            ],
            'home_assert_database_has' => [
                'total_points' => 3,
            ],
            'guest_assert_database_has' => [
                'total_points' => 3,
            ],
        ]),
    ];
    yield 'home win by calculation_type_id two' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'outward_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 25,
                'guest_points_legs' => 50,
                'home_points_games' => 25,
                'guest_points_games' => 50,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),
            ],
            'home_assert_database_has' => [
                'total_points' => 6,
            ],
            'guest_assert_database_has' => [
                'total_points' => 0,
            ],
        ]),
    ];
    yield 'guest win by calculation_type_id two' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'outward_game' => [
                'home_points_legs' => 25,
                'guest_points_legs' => 50,
                'home_points_games' => 25,
                'guest_points_games' => 50,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(36),
                'ended_at' => now()->subHours(34),
            ],
            'back_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
                'started_at' => now()->subHours(30),
                'ended_at' => now()->subHours(28),
            ],
            'home_assert_database_has' => [
                'total_points' => 0,
            ],
            'guest_assert_database_has' => [
                'total_points' => 6,
            ],
        ]),
    ];
    yield 'no calculation for future games' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'outward_game' => [
                'home_points_legs' => 25,
                'guest_points_legs' => 50,
                'home_points_games' => 25,
                'guest_points_games' => 50,
                'has_an_overtime' => false,
            ],
            'back_game' => [
                'home_points_legs' => 50,
                'guest_points_legs' => 25,
                'home_points_games' => 50,
                'guest_points_games' => 25,
                'has_an_overtime' => false,
            ],
            'home_assert_database_has' => [
                'total_points' => 0,
            ],
            'guest_assert_database_has' => [
                'total_points' => 0,
            ],
        ]),
    ];
});

it('updates the total points', function (Fluent $fluent) {
    $federation = FederationFactory::new()->create(['calculation_type_id' => $fluent->calculation_type_id]);
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    GameDayFactory::new()
        ->for($gameSchedule)
        ->create(
            [
                'started_at' => now()->subDays(2),
                'ended_at' => now()->subDays(1),
            ]
        );

    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new(
            [
                'started_at' => now()->subDays(2),
                'ended_at' => now()->subDays(1),
            ]
        )->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create($fluent->outward_game);

    GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new(
            [
                'started_at' => now()->subDays(2),
                'ended_at' => now()->subDays(1),
            ]
        )->for($gameSchedule))
        ->for($guestTeam, 'homeTeam')
        ->for($homeTeam, 'guestTeam')
        ->create($fluent->back_game);

    $totalTeamPointHome = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->for($homeTeam)
        ->create();
    $totalTeamPointDataHome = TotalTeamPointData::createFromTotalTeamPointWithRecalculation($totalTeamPointHome);

    $totalTeamPointGuest = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->for($guestTeam)
        ->create();
    $totalTeamPointDataGuest = TotalTeamPointData::createFromTotalTeamPointWithRecalculation($totalTeamPointGuest);

    app(UpdateTotalTeamPointsAction::class)->execute($totalTeamPointHome, $totalTeamPointDataHome);
    app(UpdateTotalTeamPointsAction::class)->execute($totalTeamPointGuest, $totalTeamPointDataGuest);

    tap(
        $totalTeamPointHome->refresh(),
        function (TotalTeamPoint $totalTeamPointHome) use ($fluent) {
            $this->assertDatabaseHas(
                TotalTeamPoint::class,
                array_merge(
                    [
                        'id' => $totalTeamPointHome->id,
                    ],
                    $fluent->home_assert_database_has
                )
            );
        }
    );
    tap(
        $totalTeamPointGuest->refresh(),
        function (TotalTeamPoint $totalTeamPointGuest) use ($fluent) {
            $this->assertDatabaseHas(
                TotalTeamPoint::class,
                array_merge(
                    [
                        'id' => $totalTeamPointGuest->id,
                    ],
                    $fluent->guest_assert_database_has
                )
            );
        }
    );
})->with('entriesUpdateTotalTeamPointsAction');
