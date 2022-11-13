<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Throwable;

class UpdateOrCreatePlayerAction
{
    /**
     * @throws Throwable
     */
    public function execute(PlayerData $playerData, ?Player $player = null): Player
    {
        try {
            return DB::transaction(function () use ($playerData, $player) {
                if (is_null($player)) {
                    $player = new Player();
                }

                $player->fill($playerData->toArray());
                $player->team_id = $playerData->team_id;

                $player->save();

                return $player;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
