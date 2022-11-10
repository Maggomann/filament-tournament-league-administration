<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\DTO;

use Spatie\LaravelData\Data;

class FederationData extends Data
{
    public function __construct(
        public null|int $id,
        public int $calculation_type_id,
        public string $name
    ) {
    }
}
