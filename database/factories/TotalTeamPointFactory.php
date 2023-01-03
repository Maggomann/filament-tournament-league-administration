<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint>
 */
class TotalTeamPointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint>
     */
    protected $model = TotalTeamPoint::class;

    public function definition(): array
    {
        return [
            'total_number_of_encounters' => 0,
            'total_wins' => 0,
            'total_defeats' => 0,
            'total_draws' => 0,
            'total_victory_after_defeats' => 0,
            'total_home_points_legs' => 0,
            'total_guest_points_legs' => 0,
            'total_home_points_games' => 0,
            'total_guest_points_games' => 0,
            'total_points' => 0,
        ];
    }
}
