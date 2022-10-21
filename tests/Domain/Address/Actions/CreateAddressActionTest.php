<?php

use Database\Factories\PlayerFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\CreateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\CreateAddressData;
use Maggomann\LaravelAddressable\Models\Address;

dataset('CreateAdresses', function () {
    yield fn () => CreateAddressData::create([
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

    yield fn () => CreateAddressData::create([
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

it('creates an address', function (CreateAddressData $createAddressData) {
    $player = PlayerFactory::new()->create();

    $address = app(CreateAddressAction::class)->execute($player, $createAddressData);

    $this->assertDatabaseHas(
        Address::class,
        collect($createAddressData->toArray())
            ->merge([
                'id' => $address->id,
                'addressable_id' => $player->id,
                'addressable_type' => $player->getMorphClass(),
            ])
            ->toArray()
    );
})->with('CreateAdresses');
