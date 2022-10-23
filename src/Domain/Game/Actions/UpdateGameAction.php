<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Throwable;

class UpdateGameAction
{
    /**
     * @throws Throwable
     */
    public function execute(Game $Game, GameData $GameData): Game
    {
        try {
            return DB::transaction(function () use ($Game, $GameData) {
                $Game->fill($GameData->toArray());
                $Game->federation_id = $GameData->federation_id;
                $Game->gameschedulable_type = $GameData->gameschedulable_type;
                $Game->gameschedulable_id = $GameData->gameschedulable_id;

                $Game->save();

                return $Game;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
