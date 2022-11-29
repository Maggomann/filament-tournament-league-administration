<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Contracts\AddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Traits\HasMakeableAddress;
use Maggomann\FilamentTournamentLeagueAdministration\Models\EventLocation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\LaravelAddressable\Models\Address;

class UpdateOrCreateEventLocationAddressAction implements AddressAction
{
    use HasMakeableAddress;

    protected EventLocation $eventLocation;

    protected EventLocationAddressData $eventLocationAddressData;

    protected FreeTournament $freeTournament;

    /**
     * @throws ModelNotFoundException
     */
    public function execute(FreeTournament $freeTournament, EventLocationAddressData $eventLocationAddressData, ?Address $address = null): Address
    {
        return DB::transaction(function () use ($freeTournament, $eventLocationAddressData, $address) {
            $this->eventLocationAddressData = $eventLocationAddressData;
            $this->freeTournament = $freeTournament;

            return $this->firstOrCreateFreeTournamentAddress($address)
                ->firstOrCreateEventLocation()
                ->firstOrCreateEventLocationAddress()
                ->freeTournamentAddress();
        });
    }

    private function firstOrCreateFreeTournamentAddress(?Address $address = null): self
    {
        $address = $this->makeAddress($this->eventLocationAddressData, $address);

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
            $this->eventLocationAddressData,
            $this->eventLocation->addresses()
                ->where($this->eventLocationAddressData->toArray())
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
}
