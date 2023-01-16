<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\EventLocation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\LaravelAddressable\Domain\Actions\UpdateOrCreateAddressAction;
use Maggomann\LaravelAddressable\Models\Address;

class UpdateOrCreateEventLocationAddressAction
{
    protected EventLocation $eventLocation;

    /**
     * @throws ModelNotFoundException
     */
    public function execute(FreeTournament $freeTournament, EventLocationAddressData $eventLocationAddressData, ?Address $address = null): Address
    {
        return DB::transaction(function () use ($freeTournament, $eventLocationAddressData, $address) {
            // TODO: refactor - make updateOrCreateEventLocation
            $this->firstOrCreateEventLocation();

            app(UpdateOrCreateAddressAction::class)->execute(
                $this->eventLocation,
                $eventLocationAddressData,
                $this->eventLocation
                    ->addresses()
                    ->where($eventLocationAddressData->toArray())
                    ->first()
            );

            return app(UpdateOrCreateAddressAction::class)->execute(
                $freeTournament,
                $eventLocationAddressData,
                $address
            );
        });
    }

    private function firstOrCreateEventLocation(): self
    {
        $this->eventLocation = EventLocation::firstOrCreate(
            ['name' => EventLocation::AS_DEFAULT_NAME],
        );

        return $this;
    }
}
