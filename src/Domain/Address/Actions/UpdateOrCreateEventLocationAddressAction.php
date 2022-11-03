<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\EventLocation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\LaravelAddressable\Models\Address;
use Throwable;

class UpdateOrCreateEventLocationAddressAction
{
    protected EventLocation $eventLocation;

    protected EventLocationAAddressData $eventLocationAAddressData;

    protected FreeTournament $freeTournament;

    /**
     * @throws Throwable
     */
    public function execute(FreeTournament $freeTournament, EventLocationAAddressData $eventLocationAAddressData, ?Address $address = null): Address
    {
        try {
            return DB::transaction(function () use ($freeTournament, $eventLocationAAddressData, $address) {
                $this->eventLocationAAddressData = $eventLocationAAddressData;
                $this->freeTournament = $freeTournament;

                return $this->firstOrCreateFreeTournamentAddress($address)
                    ->firstOrCreateEventLocation()
                    ->firstOrCreateEventLocationAddress()
                    ->freeTournamentAddress();
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function firstOrCreateFreeTournamentAddress(?Address $address = null): self
    {
        $address = $this->makeAddress($address);

        $this->freeTournament->address()->save($address);

        return $this;
    }

    private function firstOrCreateEventLocation(): self
    {
        $this->eventLocation = EventLocation::firstOrCreate(
            ['name' => EventLocation::AS_DEFAULT_NAME],
        );

        return $this;
    }

    private function firstOrCreateEventLocationAddress(): self
    {
        $address = $this->makeAddress(
            $this->eventLocation->addresses()
                ->where($this->eventLocationAAddressData->toArray())
                ->first()
        );

        $this->eventLocation->addresses()->save($address);

        return $this;
    }

    /**
     * @throws ModelNotFoundException
     */
    private function freeTournamentAddress(): Address
    {
        return $this->freeTournament->address()->firstOrFail();
    }

    private function makeAddress(?Address $address = null): Address
    {
        if (is_null($address)) {
            $address = new Address();
        }

        $address->fill($this->eventLocationAAddressData->toArray());
        $address->category_id = $this->eventLocationAAddressData->category_id;
        $address->gender_id = $this->eventLocationAAddressData->gender_id;

        return $address;
    }
}
