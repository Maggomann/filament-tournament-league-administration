
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

dataset('inputGamesRelationManagerPage', function () {
    yield 'with calculation type id 1 - points_after_draws is hidden' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 1,
            'assertSee' => [
                'game_day_id' => 'Game day',
                'home_team_id' => 'Home team',
                'guest_team_id' => 'Guest team',
                'legs' => 'Legs',
                'games' => 'Games',
                'started_at' => 'Start',
                'ended_at' => 'End',
            ],
        ]),
    ];
    yield 'with calculation type id 2 - points_after_draws is visible' => [
        'fluent' => fn () => new Fluent([
            'calculation_type_id' => 2,
            'assertSee' => [
                'game_day_id' => 'Game day',
                'home_team_id' => 'Home team',
                'guest_team_id' => 'Guest team',
                'legs' => 'Legs',
                'games' => 'Games',
                'points_after_draws' => 'Points. a. d.',
                'started_at' => 'Start',
                'ended_at' => 'End',
            ],
        ]),
    ];
});

it('can show games', function (Fluent $fluent) {
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

    livewire(GameScheduleResource\RelationManagers\GamesRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->assertSee($fluent->assertSee);
})->with('inputGamesRelationManagerPage');
