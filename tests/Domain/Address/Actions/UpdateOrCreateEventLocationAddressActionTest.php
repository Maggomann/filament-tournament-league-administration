<?php

use Database\Factories\AddressFactory;
use Database\Factories\FreeTournamentFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateOrCreateEventLocationAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAddressData;
use Maggomann\LaravelAddressable\Models\Address;

dataset('UpdateOrCreateEventLocationAddress', function () {
    yield fn () => EventLocationAddressData::create([
        'gender_id' => 1,
        'first_name' => 'first_name example',
        'last_name' => 'last_name name example',
        'name' => 'name example',
        'street_address' => 'street_address example',
        'street_addition' => 'street_addition example',
        'postal_code' => 'postal_code example',
        'city' => 'city example',
        'country_code' => 'DE',
    ]);

    yield fn () => EventLocationAddressData::create([
        'gender_id' => 1,
        'first_name' => 'first_name example 2',
        'last_name' => 'last_name name example 2',
        'name' => 'name example 2',
        'street_address' => 'street_address example 2',
        'street_addition' => 'street_addition example 2',
        'postal_code' => 'postal_code example 2',
        'city' => 'city example 2',
        'country_code' => 'DE',
    ]);
});

it('creates an address', function (EventLocationAddressData $eventLocationAddressData) {
    $freeTournament = FreeTournamentFactory::new()->create();

    $address = app(UpdateOrCreateEventLocationAddressAction::class)->execute($freeTournament, $eventLocationAddressData);

    $this->assertDatabaseHas(
        Address::class,
        collect($eventLocationAddressData->toArray())
            ->merge([
                'id' => $address->id,
                'addressable_id' => $freeTournament->id,
                'addressable_type' => $freeTournament->getMorphClass(),
            ])
            ->toArray()
    );
})->with('UpdateOrCreateEventLocationAddress');

it('updates an address', function (EventLocationAddressData $eventLocationAddressData) {
    $freeTournament = FreeTournamentFactory::new()->create();

    $address = AddressFactory::new()
        ->create([
            'addressable_id' => $freeTournament->id,
            'addressable_type' => $freeTournament->getMorphClass(),
        ]);

    $address = app(UpdateOrCreateEventLocationAddressAction::class)->execute($address->addressable, $eventLocationAddressData, $address);

    $this->assertDatabaseHas(Address::class, $eventLocationAddressData->toArray());
})->with('UpdateOrCreateEventLocationAddress');
