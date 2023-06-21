<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class GuestPlayerTwoSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record): Collection
    {
        $gameId = $get('game_id');

        return Game::with('guestPlayers')->find($gameId)
            ?->guestPlayers
            ?->pluck('name', 'id');
    }
}
