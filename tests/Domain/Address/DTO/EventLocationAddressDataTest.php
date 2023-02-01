<?php

use Illuminate\Support\Arr;
use Maggomann\Addressable\Domain\DTO\AddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAddressData;

beforeEach(function () {
    $this->validParams = [
        'gender_id' => null,
        'category_id' => 1,
        'first_name' => null,
        'last_name' => null,
        'name' => null,
        'street_address' => 'street_address example',
        'street_addition' => 'street_addition example',
        'postal_code' => 'postal_code example',
        'city' => 'city example',
        'country_code' => 'DE',
        'state' => null,
        'company' => null,
        'job_title' => null,
        'latitude' => null,
        'longitude' => null,
        'is_preferred' => false,
        'is_main' => false,
    ];
});

it('returns a EventLocationAddressData when valid data is submitted', function () {
    $data = EventLocationAddressData::from($this->validParams);

    $this->assertInstanceOf(EventLocationAddressData::class, $data);
    $this->assertInstanceOf(AddressData::class, $data);
});

test('EventLocationAddressData throws an error when invalid data is submitted', function ($key, $value) {
    $this->expectException(TypeError::class);

    EventLocationAddressData::from(
        Arr::set($this->validParams, $key, $value)
    );
})->with([
    ['gender_id', 'invalid'],
    ['category_id', 'invalid'],
    ['first_name', []],
    ['last_name', []],
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
