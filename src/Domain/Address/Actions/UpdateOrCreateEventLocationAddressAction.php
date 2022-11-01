<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\LaravelAddressable\Models\Address;
use Throwable;

class UpdateOrCreateEventLocationAddressAction
{
    /**
     * @throws Throwable
     */
    public function execute(FreeTournament $freeTournament, EventLocationAAddressData $eventLocationAAddressData, ?Address $address = null): Address
    {
        try {
            return DB::transaction(function () use ($freeTournament, $address, $eventLocationAAddressData) {
                if (is_null($address)) {
                    $address = new Address();
                }

                $address->fill($eventLocationAAddressData->toArray());
                $address->category_id = $eventLocationAAddressData->category_id;
                $address->gender_id = $eventLocationAAddressData->gender_id;

                $freeTournament->addresses()->save($address);

                return $address;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
