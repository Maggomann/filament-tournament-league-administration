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
        $playerTwo = $get('guest_player_id_2');

        $game = Game::with([
            'guestPlayers' => fn ($query) => ($playerTwo) ? $query->where('id', '!=', $playerTwo) : $query,
        ])
            ->when($record, function ($query) use ($set, $record) {
                if ($playerId = $record->firstGuestPlayer()?->id) {
                    $set('guest_player_id_1', $playerId);
                }

                return $query;
            })
            ->find($gameId);

        return $game
            ?->guestPlayers
            ?->pluck('name', 'id');
    }
}
