<?php

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;

beforeEach(function () {
    $this->validParams = [
        'id' => 1,
        'team_id' => 1,
        'name' => 'valid string',
        'slug' => 'valid string',
        'email' => 'valid string',
    ];
});

it('returns a PlayerData when valid data is submitted', function ($key, $value) {
    $data = PlayerData::create(
        Arr::set($this->validParams, $key, $value)
    );

    $this->assertInstanceOf(PlayerData::class, $data);
})->with([
    ['id', 1],
    ['id', null],
]);

test('PlayerData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    PlayerData::create(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', []],
    ['team_id', null],
    ['name', null],
    ['slug', null],
    ['email', []],
]);
