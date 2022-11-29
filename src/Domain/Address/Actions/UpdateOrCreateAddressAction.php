<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Contracts\AddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\PlayerAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Traits\HasMakeableAddress;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\LaravelAddressable\Models\Address;

class UpdateOrCreateAddressAction implements AddressAction
{
    use HasMakeableAddress;

    protected PlayerAddressData $playerAddressData;

    protected Player $player;

    /**
     * @throws ModelNotFoundException
     */
    public function execute(Player $player, PlayerAddressData $playerAddressData, ?Address $address = null): Address
    {
        return DB::transaction(function () use ($player, $playerAddressData, $address) {
            $this->playerAddressData = $playerAddressData;
            $this->player = $player;

            return $this->firstOrCreatePlayerAddress($address)
                ->playerAddress();
        });
    }

    private function firstOrCreatePlayerAddress(?Address $address = null): self
    {
        $address = $this->makeAddress($this->playerAddressData, $address);

        $this->player->address()->save($address);

        return $this;
    }

    /**
     * @throws ModelNotFoundException
     */
    private function playerAddress(): Address
    {
        return $this->player->address()->firstOrFail();
    }
}
