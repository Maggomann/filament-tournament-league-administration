<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\CreateAddressData;
use Maggomann\LaravelAddressable\Models\Address;
use Throwable;

class CreateAddressAction
{
    /**
     * @throws Throwable
     */
    public function execute(MorphMany $morphMany, CreateAddressData $createAddressData): Address
    {
        try {
            return DB::transaction(function () use ($morphMany, $createAddressData) {
                $address = $morphMany->create($createAddressData->toArray());

                $address->category_id = $createAddressData->category_id;
                $address->gender_id = $createAddressData->gender_id;

                $address->save();

                return $address;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
