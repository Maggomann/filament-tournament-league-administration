<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions;

use Closure;
use Illuminate\Support\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\Traits\HasGameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\Traits\HasTeamsCollection;

class HomeTeamSelect
{
    use HasGameSchedule;
    use HasTeamsCollection;

    public static function options(Closure $get, ?Game $record): Collection
    {
        $gameScheduleId = $get('game_schedule_id');
        $otherTeamId = [$get('guest_team_id')];

        if (! $record) {
            if (! $gameScheduleId) {
                return collect([]);
            }

            return self::collection($gameScheduleId, $otherTeamId);
        }

        $recordGameScheduleById = $record->gameSchedule?->id;

        if ($recordGameScheduleById === $gameScheduleId) {
            return self::collection($gameScheduleId, $otherTeamId, $record->gameSchedule);
        }

        return self::collection($gameScheduleId, $otherTeamId);
    }
}
