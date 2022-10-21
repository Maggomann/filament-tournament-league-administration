<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO;

use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Spatie\DataTransferObject\DataTransferObject;

class GameScheduleData extends DataTransferObject
{
    public ?int $id;

    public int $federation_id;

    public string $gameschedulable_type;

    public int $gameschedulable_id;

    public string $name;

    public string $started_at;

    public string $ended_at;

    public int $game_days = 0;

    /**
     * @param  array<mixed>  $args
     */
    public static function create(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        $league = League::findOrFail(Arr::get($args, 'gameschedulable_id'));

        Arr::set($args, 'gameschedulable_type', $league->getMorphClass());
        Arr::set($args, 'gameschedulable_id', $league->id);

        return new self($args);
    }
}