<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions;

use Illuminate\Support\Facades\DB;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators\CalculatorManager;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Throwable;

class RecalculateTotalGamePointsAction
{
    protected Game $game;

    /**
     * @throws Throwable
     */
    public function execute(Game $game): void
    {
        try {
            $this->game = $game;

            DB::transaction(function () {
                // FIXME: prozedural
                // TODO: refactoring because this ist only brainstorming and absolut shit :-)

                $this->saveTotalTeamPoints($this->game->homeTeam);

                $this->saveTotalTeamPoints($this->game->guestTeam);
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function saveTotalTeamPoints(Team $team): TotalTeamPoint
    {
        $totalTeamPoint = TotalTeamPoint::query()
            ->where('game_schedule_id', $this->game->gameSchedule->id)
            ->where('team_id', $team->id)
            ->first();

        if (is_null($totalTeamPoint)) {
            $totalTeamPoint = new TotalTeamPoint();
            $totalTeamPoint->game_schedule_id = $this->game->gameSchedule->id;
            $totalTeamPoint->team_id = $team->id;

            $totalTeamPoint->save();
        }

        $gameResult = $this->game->load('gameSchedule', 'games', 'guestGames')->recalculate($team)->first();

        $totalTeamPoint->fill([
            'total_number_of_encounters' => $gameResult->total_number_of_encounters,
            'total_wins' => $gameResult->total_wins,
            'total_defeats' => $gameResult->total_defeats,
            'total_draws' => $gameResult->total_draws,
            'total_victory_after_defeats' => $gameResult->total_victory_after_defeats,
            'total_home_points_legs' => ((int) $gameResult->games_sum_home_points_legs + (int) $gameResult->games_sum_guest_points_legs),
            'total_guest_points_legs' => ((int) $gameResult->guest_games_sum_home_points_legs + (int) $gameResult->guest_games_sum_guest_points_legs),
            'total_home_points_games' => ((int) $gameResult->games_sum_home_points_games + (int) $gameResult->games_sum_guest_points_games),
            'total_guest_points_games' => ((int) $gameResult->guest_games_sum_guest_points_games + (int) $gameResult->guest_games_sum_home_points_games),
            'total_home_points_from_games_and_legs' => ((int) $gameResult->games_sum_home_points_legs
                + (int) $gameResult->games_sum_guest_points_legs
                + (int) $gameResult->games_sum_home_points_games
                + (int) $gameResult->games_sum_guest_points_games
            ),
            'total_guest_points_from_games_and_legs' => ((int) $gameResult->guest_games_sum_home_points_legs
                + (int) $gameResult->guest_games_sum_guest_points_legs
                + (int) $gameResult->guest_games_sum_guest_points_games
                + (int) $gameResult->guest_games_sum_home_points_games
            ),
        ]);

        $totalTeamPoint->total_points = CalculatorManager::make($totalTeamPoint)->recalculate();
        $totalTeamPoint->save();

        return $totalTeamPoint;
    }
}
