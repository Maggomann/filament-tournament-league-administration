<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO\FreeTournamentData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Throwable;

class UpdateOrCreateFreeTournamentAction
{
    /**
     * @throws Throwable
     */
    public function execute(FreeTournamentData $freeTournamentData, ?FreeTournament $freeTournament = null): FreeTournament
    {
        try {
            return DB::transaction(function () use ($freeTournamentData, $freeTournament) {
                if (is_null($freeTournament)) {
                    $freeTournament = new FreeTournament();
                }

                $freeTournament->fill($freeTournamentData->toArray());
                $freeTournament->mode_id = $freeTournamentData->mode_id;
                $freeTournament->dart_type_id = $freeTournamentData->dart_type_id;
                $freeTournament->qualification_level_id = $freeTournamentData->qualification_level_id;

                $freeTournament->save();

                return $freeTournament;
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
