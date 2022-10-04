<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\Federation\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class FederationData extends DataTransferObject
{
    public ?int $id;

    public int $calculation_type_id;

    public string $name;

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
