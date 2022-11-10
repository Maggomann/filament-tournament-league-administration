<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\DTO;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;

class GameDayData extends Data
{
    public function __construct(
        public null|int $id,
        public int $game_schedule_id,
        public int $day,
        #[Date]
        public string $started_at,
        #[Date]
        public string $ended_at
    ) {
    }
}
