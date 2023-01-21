<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions;

use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\EventLocation;
use Maggomann\LaravelAddressable\Models\Address;

class EventLocationSelect
{
    public static function options(): Collection
    {
        return EventLocation::firstWhere('name', EventLocation::AS_DEFAULT_NAME)
            ?->addresses
            ->mapWithKeys(function (Address $address) {
                // TODO: Format with package??
                $value = '';

                if ($address->company) {
                    $value .= $address->company;
                    $value .= ' | ';
                }

                $value .= "{$address->first_name} {$address->last_name} | ";
                $value .= "{$address->street_address} | {$address->postal_code} {$address->city}";

                return [
                    $address->id => $value,
                ];
            }) ?? collect([]);
    }
}
