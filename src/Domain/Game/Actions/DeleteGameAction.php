<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Throwable;

class DeleteGameAction
{
    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            DB::transaction(function () use ($game): void {
                $game->days()->delete();
                $game->teams()->delete();
                $game->players()->delete();

                $game->delete();
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
