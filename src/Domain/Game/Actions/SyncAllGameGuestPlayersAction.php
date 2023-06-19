<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class SyncAllGameGuestPlayersAction
{
    public function execute(Game $game): Game
    {
        return DB::transaction(function () use ($game) {
            $playerIds = Player::where('tournament_league_players.team_id', $game->guestTeam->id)->pluck('id');

            $pivotData = $playerIds->mapWithKeys(function ($playerId) {
                return [$playerId => ['is_guest' => true]];
            })->all();

            $game->guestPlayers()->sync($pivotData);

            return $game;
        });
    }
}
