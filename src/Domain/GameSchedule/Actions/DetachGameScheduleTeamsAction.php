<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
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
                /** @var Builder|Relation $relationship */
                $relationship = $livewire->getRelationship();
                /** @var GameSchedule $gameSchedule */
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
