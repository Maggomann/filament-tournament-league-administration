<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameEncounterData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class UpdateOrCreateGameEncounterAction
{
    public function execute(Game $game, GameEncounterData $gameEncounterData, ?GameEncounter $gameEncounter = null): GameEncounter
    {
        return DB::transaction(function () use ($gameEncounterData, $gameEncounter) {
            if (is_null($gameEncounter)) {
                $gameEncounter = new GameEncounter();
            }

            $gameEncounter->fill($gameEncounterData->toArray());
            $gameEncounter->game_id = $gameEncounterData->game_id;
            $gameEncounter->game_encounter_type_id = $gameEncounterData->game_encounter_type_id;

            $gameEncounter->save();

            $gameEncounter->homePlayers()->sync([
                $gameEncounterData->home_player_id_1 => ['is_home' => true],
                $gameEncounterData->home_player_id_2 => ['is_home' => true],
            ]);

            $gameEncounter->guestPlayers()->sync([
                $gameEncounterData->guest_player_id_1 => ['is_guest' => true],
                $gameEncounterData->guest_player_id_2 => ['is_guest' => true],
            ]);

            // TODO: Update or create total game points

            return $gameEncounter;
        });
    }
}
