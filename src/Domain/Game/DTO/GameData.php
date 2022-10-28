<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO;

use Illuminate\Support\Arr;
use Spatie\DataTransferObject\DataTransferObject;

class GameData extends DataTransferObject
{
    public ?int $id;

    public int $game_schedule_id;

    public int $game_day_id;

    public int $home_team_id;

    public int $guest_team_id;

    public int $home_points_legs;

    public int $guest_points_legs;

    public int $home_points_games;

    public int $guest_points_games;

    public bool $has_an_overtime = false;

    public int $home_points_after_draw = 0;

    public int $guest_points_after_draw = 0;

    public string $started_at;

    public string $ended_at;

    /**
     * @param  array<mixed>  $args
     */
    public static function create(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        if (Arr::get($args, 'has_an_overtime') === false) {
            Arr::set($args, 'home_points_after_draw', 0);
            Arr::set($args, 'guest_points_after_draw', 0);
        }

        return new self($args);
    }
}
