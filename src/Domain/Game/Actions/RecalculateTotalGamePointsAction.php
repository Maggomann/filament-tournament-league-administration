<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators\CalculatorManager;
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
            'total_points_of_legs' => $this->totalPointsOfLegs($gameSchedule, $team),
            'total_wins' => $this->totalWins($gameSchedule, $team),
            'total_defeats' => $this->totalDefeats($gameSchedule, $team),
            'total_draws' => $this->totalDraws($gameSchedule, $team),
            'total_victory_after_defeats' => $this->totalVictoryAfterDefeats($gameSchedule, $team),
            'total_home_points_legs' => $this->totalHomePointsOfLegs($gameSchedule, $team),
            'total_guest_points_legs' => $this->totalGuestPointsOfLegs($gameSchedule, $team),
            'total_home_points_games' => $this->totalHomePointsOfGames($gameSchedule, $team),
            'total_guest_points_games' => $this->totalGuestPointsOfGames($gameSchedule, $team),
            'total_home_points_from_games_and_legs' => $this->totalHomePointsFromGamesAndLegs($gameSchedule, $team),
            'total_guest_points_from_games_and_legs' => $this->totalGuestPointsFromGamesAndLegs($gameSchedule, $team),
        ]);

        $totalTeamPoint->save();

        $totalTeamPoint->total_points = CalculatorManager::make($totalTeamPoint)->recalculate();
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

    private function totalPointsOfLegs(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
                ->games()
                ->where('home_team_id', $team->id)
                ->sum('home_points_legs') +
            $gameSchedule
                ->games()
                ->where('guest_team_id', $team->id)
                ->sum('guest_points_legs');
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

    private function totalVictoryAfterDefeats(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
            ->games()
            ->where(function ($query) use ($team) {
                $query->where(function ($subQuery) use ($team) {
                    $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_after_draw > guest_points_after_draw');
                })->orWhere(function ($subQuery) use ($team) {
                    $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_after_draw < guest_points_after_draw');
                });
            })
            ->count();
    }

    private function totalHomePointsOfLegs(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
                ->games()
                ->where('home_team_id', $team->id)
                ->sum('home_points_legs') +
            $gameSchedule
                ->games()
                ->where('guest_team_id', $team->id)
                ->sum('guest_points_legs');
    }

    private function totalGuestPointsOfLegs(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
                ->games()
                ->where('home_team_id', $team->id)
                ->sum('guest_points_legs') +
            $gameSchedule
                ->games()
                ->where('guest_team_id', $team->id)
                ->sum('home_points_legs');
    }

    private function totalHomePointsOfGames(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
                ->games()
                ->where('home_team_id', $team->id)
                ->sum('home_points_games') +
            $gameSchedule
                ->games()
                ->where('guest_team_id', $team->id)
                ->sum('guest_points_games');
    }

    private function totalGuestPointsOfGames(GameSchedule $gameSchedule, Team $team): int
    {
        return $gameSchedule
                ->games()
                ->where('home_team_id', $team->id)
                ->sum('guest_points_games') +
            $gameSchedule
                ->games()
                ->where('guest_team_id', $team->id)
                ->sum('home_points_games');
    }

    private function totalHomePointsFromGamesAndLegs(GameSchedule $gameSchedule, Team $team): int
    {
        return once(fn () => $this->totalHomePointsOfLegs($gameSchedule, $team) + $this->totalHomePointsOfGames($gameSchedule, $team));
    }

    private function totalGuestPointsFromGamesAndLegs(GameSchedule $gameSchedule, Team $team): int
    {
        return once(fn () => $this->totalGuestPointsOfLegs($gameSchedule, $team) + $this->totalGuestPointsOfGames($gameSchedule, $team));
    }
}
