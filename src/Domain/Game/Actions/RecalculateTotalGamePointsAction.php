<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Throwable;

class RecalculateTotalGamePointsAction
{
    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            DB::transaction(function () use ($game) {
                // FIXME: prozedural
                // TODO: refactoring because this ist only brainstorming and absolut shit :-)

                // $table->id();
                // $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
                // $table->unsignedBigInteger('team_id')->nullable()->index();
                // $table->integer('total_number_of_encounters')->nullable(); // Anzahl Begegnungen
                // $table->integer('total_wins')->nullable();
                // $table->integer('total_defeats')->nullable();
                // $table->integer('total_draws')->nullable();
                // $table->integer('total_victory_after_defeats')->nullable();
                // $table->integer('total_home_points_games')->nullable();
                // $table->integer('total_guest_points_games')->nullable();
                // $table->integer('total_home_points_after_draw')->nullable();
                // $table->integer('total_guest_points_after_draw')->nullable();

                $this->saveTotalTeamPoints($game->gameSchedule, $game->homeTeam);
                $this->saveTotalTeamPoints($game->gameSchedule, $game->guestTeam);
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function saveTotalTeamPoints(GameSchedule $gameSchedule, Team $team): void
    {
        $totalTeamPoint = TotalTeamPoint::query()
            ->where('game_schedule_id', $gameSchedule->id)
            ->where('team_id', $team->id)
            ->first();

        if (is_null($totalTeamPoint)) {
            $totalTeamPoint = new TotalTeamPoint();
            $totalTeamPoint->game_schedule_id = $gameSchedule->id;
            $totalTeamPoint->team_id = $team->id;

            $totalTeamPoint->save();
        }

        $totalTeamPoint->fill([
            'total_number_of_encounters' => $this->totalNumberOfEncounters($gameSchedule, $team),
            'total_wins' => $this->totalWins($gameSchedule, $team),
            'total_defeats' => $this->totalDefeats($gameSchedule, $team),
            'total_draws' => $this->totalDraws($gameSchedule, $team),
        ]);

        $totalTeamPoint->save();
    }

    private function totalNumberOfEncounters(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
            ->games()
            ->where(function ($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('guest_team_id', $team->id);
            })
            ->count();
    }

    private function totalWins(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
            ->games()
            ->where(function ($query) use ($team) {
                $query->where(function ($subQuery) use ($team) {
                    $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_games > guest_points_games');
                })->orWhere(function ($subQuery) use ($team) {
                    $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_games < guest_points_games');
                });
            })
            ->count();
    }

    private function totalDefeats(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
            ->games()
            ->where(function ($query) use ($team) {
                $query->where(function ($subQuery) use ($team) {
                    $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_games < guest_points_games');
                })->orWhere(function ($subQuery) use ($team) {
                    $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_games > guest_points_games');
                });
            })
            ->count();
    }

    private function totalDraws(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
            ->games()
            ->whereRaw('home_points_games = guest_points_games')
            ->where(function ($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('guest_team_id', $team->id);
            })
            ->count();
    }
}
