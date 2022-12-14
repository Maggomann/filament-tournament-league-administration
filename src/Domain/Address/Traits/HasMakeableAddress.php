<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Traits;

use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Contracts\AddressData;
use Maggomann\LaravelAddressable\Models\Address;

trait HasMakeableAddress
{
    public function makeAddress(AddressData $addressData, ?Address $address = null): Address
    {
        if (is_null($address)) {
            $address = new Address();
        }

        $address->fill($addressData->toArray());
        $address->category_id = $addressData->category_id;
        $address->gender_id = $addressData->gender_id;

        return $address;
    }
}
