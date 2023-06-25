<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class SyncAllGameHomePlayersAction
{
    public function execute(Game $game): Game
    {
        return DB::transaction(function () use ($game) {
            $playerIds = Player::where('tournament_league_players.team_id', $game->homeTeam->id)->pluck('id');

            $pivotData = $playerIds->mapWithKeys(function ($playerId) {
                return [$playerId => ['is_home' => true]];
            })->all();

            $game->homePlayers()->sync($pivotData);

            return $game;
        });
    }
}
