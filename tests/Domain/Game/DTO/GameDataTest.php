<?php

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Domain\Address\DTO\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->validParams = [
        'id' => null,
        'game_schedule_id' => 1,
        'game_day_id' => 1,
        'home_team_id' => 1,
        'guest_team_id' => 1,
        'home_points_legs' => 1,
        'guest_points_legs' => 1,
        'home_points_games' => 1,
        'guest_points_games' => 1,
        'has_an_overtime' => true,
        'home_points_after_draw' => 1,
        'guest_points_after_draw' => 2,
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ];
});

it('returns a GameData when valid data is submitted', function () {
    $data = GameData::create($this->validParams);

    $this->assertInstanceOf(GameData::class, $data);
});

test('GameData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    GameData::create(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', 'invalid'],
    ['id', []],
    ['game_schedule_id', 'invalid'],
    ['game_schedule_id', []],
    ['game_day_id', 'invalid'],
    ['game_day_id', []],
    ['home_team_id', 'invalid'],
    ['home_team_id', []],
    ['guest_team_id', 'invalid'],
    ['guest_team_id', []],
    ['home_points_legs', 'invalid'],
    ['home_points_legs', []],
    ['guest_points_legs', 'invalid'],
    ['guest_points_legs', []],
    ['home_points_games', 'invalid'],
    ['home_points_games', []],
    ['guest_points_games', 'invalid'],
    ['guest_points_games', []],
    ['has_an_overtime', []],
    ['has_an_overtime', null],
    ['home_points_after_draw', 'invalid'],
    ['home_points_after_draw', []],
    ['guest_points_after_draw', 'invalid'],
    ['guest_points_after_draw', []],
    ['started_at', []],
    ['ended_at', []],
]);

test('GameData throws an ValidationException when invalid data is submitted', function ($key, $value) {
    $this->expectException(ValidationException::class);

    GameData::validate(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['started_at', 'invalid'],
    ['ended_at', 'invalid'],
]);
