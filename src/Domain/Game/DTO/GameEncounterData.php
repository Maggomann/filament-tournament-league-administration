<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO;

use Spatie\LaravelData\Data;

class GameEncounterData extends Data
{
    public function __construct(
        public null|int $id,
        public int $game_id,
        public int $game_encounter_type_id,
        public int $order,
        public int $home_team_win,
        public int $home_team_defeat,
        public int $guest_team_win,
        public int $guest_team_defeat,
        public int $home_team_points_leg,
        public int $guest_team_points_leg,
        public null|int $home_player_id_1,
        public null|int $home_player_id_2,
        public null|int $guest_player_id_1,
        public null|int $guest_player_id_2,
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

        return static::from($args);
    }
}
