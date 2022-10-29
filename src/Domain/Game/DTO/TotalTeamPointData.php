<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO;

use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Spatie\DataTransferObject\DataTransferObject;

class TotalTeamPointData extends DataTransferObject
{
    public int $total_number_of_encounters = 0;

    public int $total_wins = 0;

    public int $total_defeats = 0;

    public int $total_draws = 0;

    public int $total_victory_after_defeats = 0;

    public int $total_home_points_legs = 0;

    public int $total_guest_points_legs = 0;

    public int $total_home_points_games = 0;

    public int $total_guest_points_games = 0;

    public int $total_home_points_from_games_and_legs = 0;

    public int $total_guest_points_from_games_and_legs = 0;

    public static function createFromTotalTeamPointWithRecalculation(TotalTeamPoint $totalTeamPoint): self
    {
        $result = $totalTeamPoint
            ->load([
                'gameSchedule',
                'team.homeGames',
                'team.guestGames',
                'team.opponentHomeGames',
                'team.opponentGuestGames',
            ])
            ->team
            ->recalculatePoints($totalTeamPoint->gameSchedule)
            ->first();

        return new self([
            'total_number_of_encounters' => $result->total_number_of_encounters,
            'total_wins' => $result->total_wins,
            'total_defeats' => $result->total_defeats,
            'total_draws' => $result->total_draws,
            'total_victory_after_defeats' => $result->total_victory_after_defeats,
            'total_home_points_legs' => ((int) $result->home_games_sum_home_points_legs + (int) $result->guest_games_sum_guest_points_legs),
            'total_guest_points_legs' => ((int) $result->opponent_home_games_sum_home_points_legs + (int) $result->opponent_guest_games_sum_guest_points_legs),
            'total_home_points_games' => ((int) $result->home_games_sum_home_points_games + (int) $result->guest_games_sum_guest_points_games),
            'total_guest_points_games' => ((int) $result->opponent_guest_games_sum_guest_points_games + (int) $result->opponent_home_games_sum_home_points_games),
            'total_home_points_from_games_and_legs' => ((int) $result->home_games_sum_home_points_legs
                + (int) $result->guest_games_sum_guest_points_legs
                + (int) $result->home_games_sum_home_points_games
                + (int) $result->guest_games_sum_guest_points_games
            ),
            'total_guest_points_from_games_and_legs' => ((int) $result->opponent_home_games_sum_home_points_legs
                + (int) $result->opponent_guest_games_sum_guest_points_legs
                + (int) $result->opponent_guest_games_sum_guest_points_games
                + (int) $result->opponent_home_games_sum_home_points_games
            ),
        ]);
    }
}
