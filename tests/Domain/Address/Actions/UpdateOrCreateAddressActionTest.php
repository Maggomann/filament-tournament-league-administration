<?php

use Database\Factories\AddressFactory;
use Database\Factories\PlayerFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateOrCreateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\PlayerAddressData;
use Maggomann\LaravelAddressable\Models\Address;

//

dataset('UpdateOrCreateAddresses', function () {
    yield fn () => PlayerAddressData::from([
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

    yield fn () => PlayerAddressData::from([
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

it('creates an address', function (PlayerAddressData $playerAddressData) {
    $player = PlayerFactory::new()->create();

    $address = app(UpdateOrCreateAddressAction::class)->execute($player, $playerAddressData);

    $this->assertDatabaseHas(
        Address::class,
        collect($playerAddressData->toArray())
            ->merge([
                'id' => $address->id,
                'addressable_id' => $player->id,
                'addressable_type' => $player->getMorphClass(),
            ])
            ->toArray()
    );
})->with('UpdateOrCreateAddresses');

it('updates an address', function (PlayerAddressData $playerAddressData) {
    $address = AddressFactory::new()->create();

    $playerAddressData->id = $address->id;

    $address = app(UpdateOrCreateAddressAction::class)->execute($address->addressable, $playerAddressData, $address);

    $this->assertDatabaseHas(Address::class, $playerAddressData->toArray());
})->with('UpdateOrCreateAddresses');
