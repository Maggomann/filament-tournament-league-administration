<?php

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Application\Team\DTO\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->validParams = [
        'id' => 1,
        'league_id' => 1,
        'name' => 'valid string',
        'slug' => 'valid string',
    ];
});

it('returns a TeamData when valid data is submitted', function ($key, $value) {
    $data = TeamData::from(
        Arr::set($this->validParams, $key, $value)
    );

    $this->assertInstanceOf(TeamData::class, $data);
})->with([
    ['id', 1],
    ['id', null],
]);

test('TeamData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    TeamData::from(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', []],
    ['league_id', null],
    ['name', null],
    ['slug', null],
]);
