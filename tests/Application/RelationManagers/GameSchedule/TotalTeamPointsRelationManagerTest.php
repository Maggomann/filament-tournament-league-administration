
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use function Pest\Livewire\livewire;

dataset('inputTotalTeamPointsRelationManagerPage', function () {
    yield 'with calculation type id 1 - total_victory_after_defeats is hidden' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'assertSee' => [
                'team_id' => 'Team',
                'total_number_of_encounters' => 'Number of encounters',
                'total_wins' => 'W',
                'total_defeats' => 'def',
                'total_draws' => 'dra',
                'legs' => 'Legs',
                'games' => 'Games',
                'total_points' => 'Points',
            ],
        ]),
    ];
    yield 'with calculation type id 2 - total_victory_after_defeats is visible' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'assertSee' => [
                'team_id' => 'Team',
                'total_number_of_encounters' => 'Number of encounters',
                'total_wins' => 'W',
                'total_defeats' => 'def',
                'total_draws' => 'dra',
                'total_victory_after_defeats' => 'VaD',
                'legs' => 'Legs',
                'games' => 'Games',
                'total_points' => 'Points',
            ],
        ]),
    ];
});

it('can show total team points ', function (Fluent $fluent) {
    $federation = FederationFactory::new()->create(['calculation_type_id' => $fluent->calculation_type_id]);
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => '2022-01-12 00:00:00',
            'ended_at' => '2022-01-12 23:59:59',
            'day' => 2,
        ]);
    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create();

    livewire(GameScheduleResource\RelationManagers\TotalTeamPointsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->assertSee($fluent->assertSee);
})->with('inputTotalTeamPointsRelationManagerPage');
