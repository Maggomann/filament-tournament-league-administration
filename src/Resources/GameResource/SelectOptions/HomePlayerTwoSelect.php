<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

class HomePlayerTwoSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record): Collection
    {
        $gameId = $get('game_id');
        $playerOne = $get('home_player_id_1');

        $game = Game::with([
            'homePlayers' => fn ($query) => ($playerOne) ? $query->where('id', '!=', $playerOne) : $query,
        ])
            ->when($record, function ($query) use ($set, $record) {
                if ($playerId = $record->secondHomePlayer()?->id) {
                    $set('home_player_id_2', $playerId);
                }

                return $query;
            })
            ->find($gameId);

        return $game
            ?->homePlayers
            ?->pluck('name', 'id');
    }
}
