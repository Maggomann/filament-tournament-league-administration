<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class FreeTournamentData extends DataTransferObject
{
    public ?int $id;

    public int $mode_id;

    public int $dart_type_id;

    public int $qualification_level_id;

    public string $name;

    public string $description;

    public int $maximum_number_of_participants;

    public int $coin_money;

    public array $prize_money_depending_on_placement;

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
