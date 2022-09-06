<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\Address\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Address\DTO\UpdateAddressData;
use Maggomann\LaravelAddressable\Models\Address;
use Throwable;

class UpdateAddressAction
{
    /**
     * @throws Throwable
     */
    public function execute(Address $address, UpdateAddressData $updateAddressData): Address
    {
        try {
            return DB::transaction(function () use ($address, $updateAddressData) {
                $address->fill($updateAddressData->toArray());
                $address->category_id = $updateAddressData->category_id;
                $address->gender_id = $updateAddressData->gender_id;

                $address->save();

                return $address;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
