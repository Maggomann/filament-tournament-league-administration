<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class HomePlayerOneSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record): Collection
    {
        $gameId = $get('game_id');

        return Game::with('homePlayers')->find($gameId)
            ?->homePlayers
            ?->pluck('name', 'id');
    }
}
