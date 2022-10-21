<?php

use Database\Factories\AddressFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\UpdateAddressData;
use Maggomann\LaravelAddressable\Models\Address;

dataset('UpdateAddresses', function () {
    yield fn () => UpdateAddressData::create([
        'gender_id' => 1,
        'category_id' => 1,
        'first_name' => 'first_name example',
        'last_name' => 'last_name name example',
        'name' => 'name example',
        'street_address' => 'street_address example',
        'street_addition' => 'street_addition example',
        'postal_code' => 'postal_code example',
        'city' => 'city example',
        'country_code' => 'DE',
        'is_preferred' => false,
        'is_main' => false,
    ]);

    yield fn () => UpdateAddressData::create([
        'gender_id' => 1,
        'category_id' => 1,
        'first_name' => 'first_name example 2',
        'last_name' => 'last_name name example 2',
        'name' => 'name example 2',
        'street_address' => 'street_address example 2',
        'street_addition' => 'street_addition example 2',
        'postal_code' => 'postal_code example 2',
        'city' => 'city example 2',
        'country_code' => 'DE',
        'is_preferred' => true,
        'is_main' => true,
    ]);
});

it('updates an address', function (UpdateAddressData $updateAddressData) {
    $address = AddressFactory::new()->create();

    $updateAddressData->id = $address->id;

    $address = app(UpdateAddressAction::class)->execute($address, $updateAddressData);

    $this->assertDatabaseHas(Address::class, $updateAddressData->toArray());
})->with('UpdateAddresses');
