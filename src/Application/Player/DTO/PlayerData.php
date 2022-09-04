<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\Player\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class PlayerData extends DataTransferObject
{
    public ?int $id;

    public int $team_id;

    public string $name;

    public string $slug;

    public ?string $email;

    public static function create(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        return new self($args);
    }
}
