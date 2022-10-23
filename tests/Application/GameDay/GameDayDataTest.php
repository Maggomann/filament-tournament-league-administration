<?php

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\DTO\GameDayData;

beforeEach(function () {
    $this->validParams = [
        'id' => 1,
        'game_schedule_id' => 1,
        'day' => 1,
        'started_at' => now(),
        'ended_at' => now()->addDay(),
    ];
});

it('returns a GameDayData when valid data is submitted', function ($key, $value) {
    $data = GameDayData::create(
        Arr::set($this->validParams, $key, $value)
    );

    $this->assertInstanceOf(GameDayData::class, $data);
})->with([
    ['id', 1],
    ['id', null],
]);

test('GameDayData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    GameDayData::create(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', []],
    ['game_schedule_id', null],
    ['day', null],
    ['started_at', []],
    ['ended_at', []],
]);
