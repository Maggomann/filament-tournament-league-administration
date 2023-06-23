<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class HomePlayerOneSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record = null): Collection
    {
        $gameId = $get('game_id');
        $playerTwo = $get('home_player_id_2');

        $game = Game::with([
            'homePlayers' => fn ($query) => ($playerTwo) ? $query->where('id', '!=', $playerTwo) : $query,
        ])
            ->when($record, function ($query) use ($set, $record) {
                if (blank($record)) {
                    return $query;
                }

                /** @var ?Player $player */
                $player = $record->firstHomePlayer();

                if ($player instanceof Player) {
                    $set('home_player_id_1', $player->id);
                }

                return $query;
            })
            ->find($gameId);

        return $game
            ?->homePlayers
            ?->pluck('name', 'id');
    }
}
