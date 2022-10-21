<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\CreateAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\LaravelAddressable\Models\Address;
use Throwable;

class CreateAddressAction
{
    /**
     * @throws Throwable
     */
    public function execute(Player $player, CreateAddressData $createAddressData): Address
    {
        try {
            return DB::transaction(function () use ($player, $createAddressData) {
                $address = new Address();
                $address->fill($createAddressData->toArray());

                $address->category_id = $createAddressData->category_id;
                $address->gender_id = $createAddressData->gender_id;

                // TODO später durch Relation erstellen
                $address->addressable_id = $player->id;
                $address->addressable_type = $player->getMorphClass();
                $address->push();

                return $address;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
