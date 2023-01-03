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

// TODO: add more combination
dataset('entriesUpdateTotalTeamPointsAction', function () {
    yield [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'home_total_points' => 2,
            'guest_total_points' => 0,
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'home_total_points' => 3,
            'guest_total_points' => 0,
        ]),
    ];
});

it('it updates the total points', function (Fluent $fluent) {
    $federation = FederationFactory::new()->create(['calculation_type_id' => $fluent->calculation_type_id]);
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    GameDayFactory::new()
        ->for($gameSchedule)
        ->create();

    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create([
            'home_points_legs' => 50,
            'guest_points_legs' => 25,
            'home_points_games' => 50,
            'guest_points_games' => 25,
            'has_an_overtime' => false,
        ]);

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
                [
                    'id' => $totalTeamPointHome->id,
                    'total_points' => $fluent->home_total_points,
                ]
            );
        }
    );
    tap(
        $totalTeamPointGuest->refresh(),
        function (TotalTeamPoint $totalTeamPointGuest) use ($fluent) {
            $this->assertDatabaseHas(
                TotalTeamPoint::class,
                [
                    'id' => $totalTeamPointGuest->id,
                    'total_points' => $fluent->guest_total_points,
                ]
            );
        }
    );
})->with('entriesUpdateTotalTeamPointsAction');
