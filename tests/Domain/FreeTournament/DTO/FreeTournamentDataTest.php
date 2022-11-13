<?php

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO\FreeTournamentData;

beforeEach(function () {
    $this->validParams = [
        'id' => null,
        'mode_id' => 1,
        'dart_type_id' => 1,
        'qualification_level_id' => 1,
        'name' => 'name example',
        'description' => 'description example',
        'maximum_number_of_participants' => 1,
        'coin_money' => 5,
        'prize_money_depending_on_placement' => ['1st place' => '1st prize'],
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ];
});

it('returns a FreeTournamentData when valid data is submitted', function () {
    $data = FreeTournamentData::from($this->validParams);

    $this->assertInstanceOf(FreeTournamentData::class, $data);
});

test('FreeTournamentData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    FreeTournamentData::from(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', 'invalid'],
    ['id', []],
    ['mode_id', 'invalid'],
    ['mode_id', []],
    ['dart_type_id', 'invalid'],
    ['dart_type_id', []],
    ['qualification_level_id', 'invalid'],
    ['qualification_level_id', []],
    ['name', []],
    ['description', []],
    ['maximum_number_of_participants', 'invalid'],
    ['maximum_number_of_participants', []],
    ['coin_money', 'invalid'],
    ['coin_money', []],
    ['prize_money_depending_on_placement', 'invalid'],
    ['started_at', []],
    ['ended_at', []],
]);

test('FreeTournamentData throws an ValidationException when invalid data is submitted', function ($key, $value) {
    $this->expectException(ValidationException::class);

    FreeTournamentData::validate(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['maximum_number_of_participants', 0],
    ['maximum_number_of_participants', 11],
    ['started_at', 'invalid'],
    ['ended_at', 'invalid'],
]);
