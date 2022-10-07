<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class GameDayData extends DataTransferObject
{
    public ?int $id;

    public int $game_schedule_id;

    public int $day;

    public string $started_at;

    public string $end;

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
