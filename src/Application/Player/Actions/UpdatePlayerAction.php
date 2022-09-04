<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\Player\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Throwable;

class UpdatePlayerAction
{
    /**
     * @throws Throwable
     */
    public function execute(Player $player, PlayerData $playerData): Player
    {
        try {
            return DB::transaction(function () use ($player, $playerData) {
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
