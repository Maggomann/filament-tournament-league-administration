<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class FreeTournamentData extends Data
{
    public function __construct(
        public null|int $id,
        public int $mode_id,
        public int $dart_type_id,
        public int $qualification_level_id,
        public string $name,
        public string $description,
        #[Min(1), Max(10), ]
        public int $maximum_number_of_participants,
        public int $coin_money,
        public array $prize_money_depending_on_placement,
        public null|string $upload_image,
        #[Date]
        public string $started_at,
        #[Date]
        public string $ended_at
    ) {
    }
}
