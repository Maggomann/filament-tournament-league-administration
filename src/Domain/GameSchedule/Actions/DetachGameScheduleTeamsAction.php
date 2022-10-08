<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\TeamsRelationManager;
use Throwable;

class DetachGameScheduleTeamsAction
{
    /**
     * @throws Throwable
     */
    public function execute(TeamsRelationManager $livewire, Collection $records): void
    {
        try {
            DB::transaction(function () use ($livewire, $records) {
                $relationship = $livewire->getRelationship();
                $gameSchedule = $relationship->getParent();

                $gameSchedule->players()->detach(
                    $gameSchedule->players()->whereIn(
                        'tournament_league_players.team_id',
                        $records->pluck('team_id')
                    )->pluck('id')
                );

                $relationship->detach($records);
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
