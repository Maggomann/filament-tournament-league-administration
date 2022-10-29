<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Throwable;

class CreateGameAction
{
    /**
     * @throws Throwable
     */
    public function execute(GameData $gameData): Game
    {
        try {
            return DB::transaction(function () use ($gameData) {
                $game = new Game();
                $game->fill($gameData->toArray());
                $game->game_schedule_id = $gameData->game_schedule_id;
                $game->game_day_id = $gameData->game_day_id;
                $game->home_team_id = $gameData->home_team_id;
                $game->guest_team_id = $gameData->guest_team_id;

                $game->save();

                app(CreateOrUpdateTotalGamePointsAction::class)->execute($game);

                return $game;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
