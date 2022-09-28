<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\DTO;

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

    public string $period_start;

    public string $period_end;

    public int $game_days = 0;

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
