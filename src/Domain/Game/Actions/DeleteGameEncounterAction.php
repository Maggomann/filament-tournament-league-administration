<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class DeleteGameEncounterAction
{
    public function execute(GameEncounter $gameEncounter): void
    {
        DB::transaction(function () use ($gameEncounter) {
            // TODO: Update total game points
            $gameEncounter->homePlayers()->sync([]);

            $gameEncounter->guestPlayers()->sync([]);

            $gameEncounter->delete();
        });
    }
}
