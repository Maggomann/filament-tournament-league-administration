<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TotalTeamPointFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateTeamPlacementsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

dataset('entriesUpdateTeamPlacementsAction', function () {
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 150,
                'team_two' => 100,
                'team_three' => 50,
            ]),
            'placements' => new Fluent([
                'team_one' => 1,
                'team_two' => 2,
                'team_three' => 3,
            ]),
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 150,
                'team_two' => 50,
                'team_three' => 100,
            ]),
            'placements' => new Fluent([
                'team_one' => 1,
                'team_two' => 3,
                'team_three' => 2,
            ]),
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 100,
                'team_two' => 150,
                'team_three' => 50,
            ]),
            'placements' => new Fluent([
                'team_one' => 2,
                'team_two' => 1,
                'team_three' => 3,
            ]),
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 100,
                'team_two' => 50,
                'team_three' => 150,
            ]),
            'placements' => new Fluent([
                'team_one' => 2,
                'team_two' => 3,
                'team_three' => 1,
            ]),
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 50,
                'team_two' => 100,
                'team_three' => 150,
            ]),
            'placements' => new Fluent([
                'team_one' => 3,
                'team_two' => 2,
                'team_three' => 1,
            ]),
        ]),
    ];
    yield [
        'fluent' => fn () => new Fluent([
            'team_points' => new Fluent([
                'team_one' => 50,
                'team_two' => 150,
                'team_three' => 100,
            ]),
            'placements' => new Fluent([
                'team_one' => 3,
                'team_two' => 1,
                'team_three' => 2,
            ]),
        ]),
    ];
});

it('updates the placements', function (Fluent $fluent) {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    $teamPointOne = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->create(['total_points' => $fluent->team_points->team_one]);

    $teamPointTwo = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->create(['total_points' => $fluent->team_points->team_two]);

    $teamPointThree = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->create(['total_points' => $fluent->team_points->team_three]);

    app(UpdateTeamPlacementsAction::class)->execute($gameSchedule);

    tap(
        $teamPointOne->refresh(),
        fn (TotalTeamPoint $teamPointOne) => $this->assertSame($fluent->placements->team_one, $teamPointOne->placement)
    );
    tap(
        $teamPointTwo->refresh(),
        fn (TotalTeamPoint $teamPointTwo) => $this->assertSame($fluent->placements->team_two, $teamPointTwo->placement)
    );
    tap(
        $teamPointThree->refresh(),
        fn (TotalTeamPoint $teamPointThree) => $this->assertSame($fluent->placements->team_three, $teamPointThree->placement)
    );
})->with('entriesUpdateTeamPlacementsAction');
