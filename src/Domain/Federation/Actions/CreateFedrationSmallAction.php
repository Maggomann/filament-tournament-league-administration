<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\DTO\FederationData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

class CreateFedrationSmallAction
{
    public function execute(FederationData $federationData): Federation
    {
        return DB::transaction(function () use ($federationData) {
            $federation = new Federation();
            $federation->fill($federationData->toArray());
            $federation->calculation_type_id = $federationData->calculation_type_id;

            $federation->saveQuietly();

            return $federation;
        });
    }
}
