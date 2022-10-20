<?php

use Database\Factories\AddressFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\UpdateAddressData;
use Maggomann\LaravelAddressable\Models\Address;

it('updates an address', function () {
    $address = AddressFactory::new()->create();

    $address = app(UpdateAddressAction::class)->execute(
        $address,
        UpdateAddressData::create([
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
        ])
    );

    $this->assertDatabaseHas(Address::class, [
        'id' => $address->id,
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
});
