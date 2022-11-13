<?php

use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateOrCreateGameAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;

dataset('UpdateOrCreateGames', function () {
    yield fn () => GameData::create([
        'game_schedule_id' => ($gameSchedule = GameScheduleFactory::new()->create()) ? $gameSchedule->id : 1,
        'game_day_id' => GameDayFactory::new()->for($gameSchedule)->create()->id,
        'home_team_id' => ($closure = function () use ($gameSchedule) {
            $team = TeamFactory::new()->create();
            $team->gameSchedules()->save($gameSchedule);

            return $team;
        }) ? $closure->call($gameSchedule)->id : 1,
        'guest_team_id' => ($closure = function () use ($gameSchedule) {
            $team = TeamFactory::new()->create();
            $team->gameSchedules()->save($gameSchedule);

            return $team;
        }) ? $closure->call($gameSchedule)->id : 1,
        'home_points_legs' => 1,
        'guest_points_legs' => 1,
        'home_points_games' => 1,
        'guest_points_games' => 1,
        'has_an_overtime' => true,
        'home_points_after_draw' => 1,
        'guest_points_after_draw' => 2,
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ]);

    yield fn () => GameData::create([
        'game_schedule_id' => ($gameSchedule = GameScheduleFactory::new()->create()) ? $gameSchedule->id : 1,
        'game_day_id' => GameDayFactory::new()->for($gameSchedule)->create()->id,
        'home_team_id' => ($closure = function () use ($gameSchedule) {
            $team = TeamFactory::new()->create();
            $team->gameSchedules()->save($gameSchedule);

            return $team;
        }) ? $closure->call($gameSchedule)->id : 1,
        'guest_team_id' => ($closure = function () use ($gameSchedule) {
            $team = TeamFactory::new()->create();
            $team->gameSchedules()->save($gameSchedule);

            return $team;
        }) ? $closure->call($gameSchedule)->id : 1,
        'home_points_legs' => 3,
        'guest_points_legs' => 3,
        'home_points_games' => 3,
        'guest_points_games' => 3,
        'has_an_overtime' => false,
        'home_points_after_draw' => 0,
        'guest_points_after_draw' => 0,
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ]);
});

it('creates an game', function (GameData $gameData) {
    $game = app(UpdateOrCreateGameAction::class)->execute($gameData);

    $this->assertDatabaseHas(Game::class, $game->attributesToArray());
})->with('UpdateOrCreateGames');

it('updates an game', function (GameData $gameData) {
    $game = app(UpdateOrCreateGameAction::class)
        ->execute(
            $gameData,
            GameFactory::new()->create([
                'game_schedule_id' => null,
                'game_day_id' => null,
                'home_team_id' => null,
                'guest_team_id' => null,
            ])
        );

    $this->assertDatabaseHas(Game::class, $game->attributesToArray());
})->with('UpdateOrCreateGames');
