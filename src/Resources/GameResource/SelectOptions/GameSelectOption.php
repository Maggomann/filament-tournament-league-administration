<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Selects;

use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class GameSelectOption
{
    public static function forGameDays(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::collectionForGameDays($gameScheduleId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            $collection = $record->gameSchedule
                ?->days;

            return self::collectionForGameDays($gameScheduleId, $collection);
        }

        return self::collectionForGameDays($gameScheduleId);
    }

    public static function forHomeTeams(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');
        $otherTeamId = [$get('guest_team_id')];

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::collectionForTeams($gameScheduleId, $otherTeamId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            return self::collectionForTeams($gameScheduleId, $otherTeamId, $record->gameSchedule);
        }

        return self::collectionForTeams($gameScheduleId, $otherTeamId);
    }

    public static function forGuestTeams(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');
        $otherTeamId = [$get('home_team_id')];

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::collectionForTeams($gameScheduleId, $otherTeamId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            return self::collectionForTeams($gameScheduleId, $otherTeamId, $record->gameSchedule);
        }

        return self::collectionForTeams($gameScheduleId, $otherTeamId);
    }

    protected static function collectionForGameDays(int $gameScheduleId, ?EloquentCollection $collection = null): Collection
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

    protected static function collectionForTeams(int $gameScheduleId, array $otherTeamId, ?GameSchedule $gameSchedule = null): Collection
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
