<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\Traits\HasGameSchedule;

class GameDaySelect
{
    use HasGameSchedule;

    public static function options(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::collection($gameScheduleId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            $collection = $record->gameSchedule
                ?->days;

            return self::collection($gameScheduleId, $collection);
        }

        return self::collection($gameScheduleId);
    }

    protected static function collection(int $gameScheduleId, ?EloquentCollection $collection = null): Collection
    {
        if (! $collection) {
            $collection = self::gameSchedule($gameScheduleId)
                ?->days;
        }

        /** @var \Illuminate\Support\Collection<GameDay> $collection */
        if ($collection) {
            return $collection->mapWithKeys(fn (GameDay $gameDay) => [
                $gameDay->id => "{$gameDay->day}  - ({$gameDay->started_at} - {$gameDay->ended_at})",
            ]);
        }

        return collect([]);
    }
}
