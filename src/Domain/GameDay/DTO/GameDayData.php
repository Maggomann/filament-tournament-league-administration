<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameDay\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class GameDayData extends DataTransferObject
{
    public ?int $id;

    public int $game_schedule_id;

    public int $day;

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

        return new self($args);
    }
}
