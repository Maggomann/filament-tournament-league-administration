
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;
use function Pest\Livewire\livewire;

it('can render game list table', function () {
    $this->get(GameResource::getUrl('index'))->assertSuccessful();
});

it('can render game create form', function () {
    $this->get(GameResource::getUrl('create'))->assertSuccessful();
});

it('can render game edit form', function () {
    $gameSchedule = GameScheduleFactory::new()->create();
    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    $this->get(GameResource::getUrl('edit', [
        'record' => GameFactory::new()
            ->for($gameSchedule)
            ->for(GameDayFactory::new()->for($gameSchedule))
            ->for($homeTeam, 'homeTeam')
            ->for($guestTeam, 'guestTeam')
            ->create(),
    ]))->assertSuccessful();
});

it('can create a game', function () {
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

    livewire(GameResource\Pages\CreateGame::class)
        ->fillForm([
            'game_schedule_id' => $gameSchedule->id,
            'game_day_id' => $gameDay->id,
            'started_at' => '2022-01-12 01:00:00',
            'ended_at' => '2022-01-12 03:00:00',
            'home_team_id' => $homeTeam->id,
            'guest_team_id' => $guestTeam->id,
            'home_points_legs' => 100,
            'guest_points_legs' => 50,
            'home_points_games' => 30,
            'guest_points_games' => 15,
            'has_an_overtime' => false,
            'home_points_after_draw' => 0,
            'guest_points_after_draw' => 0,

        ])
        ->call('create')
        ->assertHasNoFormErrors();

    tap(
        Game::where([
            'game_schedule_id' => $gameSchedule->id,
            'game_day_id' => $gameDay->id,
            'home_team_id' => $homeTeam->id,
            'guest_team_id' => $guestTeam->id,
            'home_points_legs' => 100,
            'guest_points_legs' => 50,
            'home_points_games' => 30,
            'guest_points_games' => 15,
            'has_an_overtime' => false,
            'home_points_after_draw' => 0,
            'guest_points_after_draw' => 0,
        ])->first(),
        function (Game $game) use ($gameSchedule, $gameDay, $homeTeam, $guestTeam) {
            $this->assertDatabaseHas(Game::class, [
                'id' => $game->id,
                'game_schedule_id' => $gameSchedule->id,
                'game_day_id' => $gameDay->id,
                'home_team_id' => $homeTeam->id,
                'guest_team_id' => $guestTeam->id,
                'home_points_legs' => 100,
                'guest_points_legs' => 50,
                'home_points_games' => 30,
                'guest_points_games' => 15,
                'has_an_overtime' => false,
                'home_points_after_draw' => 0,
                'guest_points_after_draw' => 0,
            ]);
        }
    );
});

it('can save a game', function () {
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

    livewire(GameResource\Pages\EditGame::class, [
        'record' => $game->getRouteKey(),
    ])
        ->fillForm([
            'game_schedule_id' => $gameSchedule->id,
            'game_day_id' => $gameDay->id,
            'started_at' => '2022-01-12 01:00:00',
            'ended_at' => '2022-01-12 03:00:00',
            'home_team_id' => $homeTeam->id,
            'guest_team_id' => $guestTeam->id,
            'home_points_legs' => 100,
            'guest_points_legs' => 50,
            'home_points_games' => 30,
            'guest_points_games' => 15,
            'has_an_overtime' => false,
            'home_points_after_draw' => 0,
            'guest_points_after_draw' => 0,

        ])
        ->call('save')
        ->assertHasNoFormErrors();

    tap(
        $game->refresh(),
        function (Game $game) use ($gameSchedule, $gameDay, $homeTeam, $guestTeam) {
            $this->assertDatabaseHas(Game::class, [
                'id' => $game->id,
                'game_schedule_id' => $gameSchedule->id,
                'game_day_id' => $gameDay->id,
                'home_team_id' => $homeTeam->id,
                'guest_team_id' => $guestTeam->id,
                'home_points_legs' => 100,
                'guest_points_legs' => 50,
                'home_points_games' => 30,
                'guest_points_games' => 15,
                'has_an_overtime' => false,
                'home_points_after_draw' => 0,
                'guest_points_after_draw' => 0,
            ]);
        }
    );
});

it('can delete a game', function () {
    $federation = FederationFactory::new()->create();
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

    $game = GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create();

    livewire(GameResource\Pages\EditGame::class, [
        'record' => $game->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($game);
});
