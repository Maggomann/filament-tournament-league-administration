<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class GuestPlayerOneSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record = null): Collection
    {
        $gameId = $get('game_id');
        $playerIdTwo = $get('guest_player_id_2');

        $game = Game::with([
            'guestPlayers' => fn ($query) => ($playerIdTwo) ? $query->where('id', '!=', $playerIdTwo) : $query,
        ])
            ->when($record, function ($query) use ($set, $record) {
                if (blank($record)) {
                    return $query;
                }

                /** @var ?Player $player */
                $player = $record->firstGuestPlayer();

                if ($player instanceof Player) {
                    $set('guest_player_id_1', $player->id);
                }

                return $query;
            })
            ->find($gameId);

        return $game
            ?->guestPlayers
            ?->pluck('name', 'id');
    }
}
