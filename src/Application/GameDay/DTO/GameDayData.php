<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class GameDayData extends DataTransferObject
{
    public ?int $id;

    public int $game_schedule_id;

    public int $game_day;

    public string $start;

    public string $end;

    public static function create(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        return new self($args);
    }
}
