<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class GuestPlayerOneSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record): Collection
    {
        $gameId = $get('game_id');

        if ($record) {
            $playerId = $record->firstGuestPlayer()?->id;

            if ($playerId) {
                $set('guest_player_id_1', $playerId);
            }
        }

        return Game::with('guestPlayers')->find($gameId)
            ?->guestPlayers
            ?->pluck('name', 'id');
    }
}
