<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class TeamData extends DataTransferObject
{
    public ?int $id;

    public int $league_id;

    public string $name;

    public string $slug;

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
