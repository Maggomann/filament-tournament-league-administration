<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Selects;

use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class SelectOption
{
    public static function gameDays(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::selectOptionsForGameDays($gameScheduleId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            $collection = $record->gameSchedule
                ?->days;

            return self::selectOptionsForGameDays($gameScheduleId, $collection);
        }

        return self::selectOptionsForGameDays($gameScheduleId);
    }

    protected static function selectOptionsForGameDays(int $gameScheduleId, ?EloquentCollection $collection = null): Collection
    {
        if (! $collection) {
            $collection = self::gameSchedule($gameScheduleId)
                ?->days;
        }

        if ($collection) {
            return $collection->mapWithKeys(fn (GameDay $gameDay) => [
                $gameDay->id => "{$gameDay->day}  - ({$gameDay->started_at} - {$gameDay->ended_at})",
            ]);
        }

        return collect([]);
    }

    public static function homeTeams(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');
        $otherTeamId = [$get('guest_team_id')];

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::selectOptionsForTeams($gameScheduleId, $otherTeamId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            return self::selectOptionsForTeams($gameScheduleId, $otherTeamId, $record->gameSchedule);
        }

        return self::selectOptionsForTeams($gameScheduleId, $otherTeamId);
    }

    public static function guestTeams(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');
        $otherTeamId = [$get('home_team_id')];

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::selectOptionsForTeams($gameScheduleId, $otherTeamId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            return self::selectOptionsForTeams($gameScheduleId, $otherTeamId, $record->gameSchedule);
        }

        return self::selectOptionsForTeams($gameScheduleId, $otherTeamId);
    }

    protected static function selectOptionsForTeams(int $gameScheduleId, array $otherTeamId, ?GameSchedule $gameSchedule = null): Collection
    {
        if (! $gameSchedule) {
            return self::gameSchedule($gameScheduleId)
                ?->teams
                ?->whereNotIn('id', $otherTeamId)
                ?->pluck('name', 'id') ?? collect([]);
        }

        return $gameSchedule
            ?->teams
            ?->whereNotIn('id', $otherTeamId)
            ?->pluck('name', 'id') ?? collect([]);
    }

    protected static function gameSchedule(int $gameScheduleId): ?GameSchedule
    {
        return once(fn () => GameSchedule::with(['days', 'teams'])->find($gameScheduleId));
    }
}
