<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter>
 */
class GameEncounterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter>
     */
    protected $model = GameEncounter::class;

    public function definition(): array
    {
        return [
            'game_id' => GameFactory::new()->lazy(),
            'game_encounter_type_id' => $this->faker->randomElement([1, 2]),
            'order' => $this->faker->numberBetween($min = 1, $max = 200),
            'home_team_win' => $this->faker->numberBetween($min = 1, $max = 3),
            'home_team_defeat' => $this->faker->numberBetween($min = 1, $max = 3),
            'guest_team_win' => $this->faker->numberBetween($min = 1, $max = 3),
            'guest_team_defeat' => $this->faker->numberBetween($min = 1, $max = 3),
            'home_team_points_leg' => $this->faker->numberBetween($min = 1, $max = 200),
            'guest_team_points_leg' => $this->faker->numberBetween($min = 1, $max = 200),
        ];
    }
}
