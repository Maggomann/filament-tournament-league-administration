<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO;

use Spatie\LaravelData\Data;

class PlayerData extends Data
{
    public function __construct(
        public null|int $id,
        public int $team_id,
        public string $name,
        public string $slug,
        public null|string $email,
        public null|string $upload_image
    ) {
    }
}
