<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class UpdateOrCreatePlayerAction
{
    public function execute(PlayerData $playerData, ?Player $player = null): Player
    {
        return DB::transaction(function () use ($playerData, $player) {
            if (is_null($player)) {
                $player = new Player();
            }

            $player->fill($playerData->toArray());
            $player->team_id = $playerData->team_id;
            $player->player_role_id = $playerData->player_role_id;

            $player->save();

            return $player;
        });
    }
}
