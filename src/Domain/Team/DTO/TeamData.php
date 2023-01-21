<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO;

use Spatie\LaravelData\Data;

class TeamData extends Data
{
    public function __construct(
        public null|int $id,
        public int $league_id,
        public string $name,
        public string $slug,
        public null|string $upload_image
    ) {
    }
}
