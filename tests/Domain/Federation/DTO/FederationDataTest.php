<?php

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\DTO\FederationData;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Domain\Federation\DTO\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->validParams = [
        'id' => 1,
        'calculation_type_id' => 1,
        'name' => 'valid',
    ];
});

it('returns a FederationData when valid data is submitted', function ($key, $value) {
    $data = FederationData::from(
        Arr::set($this->validParams, $key, $value)
    );

    $this->assertInstanceOf(FederationData::class, $data);
})->with([
    ['id', 1],
    ['id', null],
]);

test('FederationData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    FederationData::from(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['id', []],
    ['calculation_type_id', null],
    ['name', null],
]);
