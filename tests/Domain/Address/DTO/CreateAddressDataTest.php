<?php

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\CreateAddressData;

beforeEach(function () {
    $this->validParams = [
        'gender_id' => 1,
        'category_id' => 1,
        'first_name' => 'first_name example',
        'last_name' => 'last_name name example',
        'name' => 'name example',
        'street_address' => 'street_address example',
        'street_addition' => 'street_addition example',
        'postal_code' => 'postal_code example',
        'city' => 'city example',
        'country_code' => null,
        'state' => null,
        'company' => null,
        'job_title' => null,
        'latitude' => null,
        'longitude' => null,
        'country_code' => 'DE',
        'is_preferred' => false,
        'is_main' => false,
    ];
});

it('returns a CreateAddressData when valid data is submitted', function () {
    $data = CreateAddressData::create($this->validParams);

    $this->assertInstanceOf(CreateAddressData::class, $data);
});

test('CreateAddressData throws an error when invalid data is submitted', function ($key, $value) {
    $this->withoutExceptionHandling();
    $this->expectException(TypeError::class);

    CreateAddressData::create(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['gender_id', 'invalid'],
    ['category_id', 'invalid'],
    ['first_name', null],
    ['last_name', null],
    ['name', []],
    ['street_address', null],
    ['street_addition', []],
    ['postal_code', []],
    ['city', null],
    ['country_code', null],
    ['state', []],
    ['company', []],
    ['job_title', []],
    ['latitude', []],
    ['longitude', []],
    ['is_preferred', null],
    ['is_main', null],
]);
