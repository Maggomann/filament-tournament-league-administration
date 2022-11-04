<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Contracts;

use Maggomann\LaravelAddressable\Models\Address;

interface AddressAction
{
    public function makeAddress(AddressData $addressData, ?Address $address = null): Address;
}
