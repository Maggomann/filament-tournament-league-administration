
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use function Pest\Livewire\livewire;

it('can show total team points ', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $gameDay = GameDayFactory::new()
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

    $game = GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create();

    livewire(GameScheduleResource\RelationManagers\TotalTeamPointsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->assertSee([
        'team_id',
        'total_number_of_encounters',
        'total_wins',
        'total_defeats',
        'total_draws',
        'total_victory_after_defeats',
        'legs',
        'games',
        'total_points',
    ]);
});
