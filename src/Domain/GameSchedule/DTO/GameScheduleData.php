<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO;

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;

class GameScheduleData extends Data
{
    public function __construct(
        public null|int $id,
        public int $federation_id,
        public int $league_id,
        public string $name,
        public int $game_days,
        #[Date]
        public string $started_at,
        #[Date]
        public string $ended_at

    ) {
    }

    /**
     * @param  array<mixed>  $args
     */
    public static function create(...$args): static
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        $league = League::findOrFail(Arr::get($args, 'league_id'));

        Arr::set($args, 'league_id', $league->id);

        return static::from($args);
    }
}
