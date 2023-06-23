<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class GuestPlayerTwoSelect
{
    public static function options(Closure $get, Closure $set, ?GameEncounter $record = null): Collection
    {
        $gameId = $get('game_id');
        $playerOne = $get('guest_player_id_1');

        $game = Game::with([
            'guestPlayers' => fn ($query) => ($playerOne) ? $query->where('id', '!=', $playerOne) : $query,
        ])
            ->when($record, function ($query) use ($set, $record) {
                if (blank($record)) {
                    return $query;
                }

                /** @var ?Player $player */
                $player = $record->secondGuestPlayer();

                if ($player instanceof Player) {
                    $set('guest_player_id_2', $player->id);
                }

                return $query;
            })
            ->find($gameId);

        return $game
            ?->guestPlayers
            ?->pluck('name', 'id');
    }
}
