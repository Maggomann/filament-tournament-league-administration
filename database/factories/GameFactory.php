<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\Game>
     */
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'game_schedule_id' => GameScheduleFactory::new()->lazy(),
            'game_day_id' => GameFactory::new()->lazy(),
            'home_team_id' => TeamFactory::new()->lazy(),
            'guest_team_id' => TeamFactory::new()->lazy(),
            'home_points_legs' => $this->faker->numberBetween($min = 1, $max = 200),
            'guest_points_legs' => $this->faker->numberBetween($min = 1, $max = 200),
            'home_points_games' => $this->faker->numberBetween($min = 1, $max = 200),
            'guest_points_games' => $this->faker->numberBetween($min = 1, $max = 200),
            'has_an_overtime' => $this->faker->boolean,
            'home_points_after_draw' => function (array $attributes) {
                return ($attributes['has_an_overtime'])
                    ? $this->faker->numberBetween($min = 1, $max = 200)
                    : 0;
            },
            'guest_points_after_draw' => function (array $attributes) {
                return ($attributes['has_an_overtime'])
                    ? $this->faker->numberBetween($min = 1, $max = 200)
                    : 0;
            },
            'started_at' => now(),
            'ended_at' => now()->addHour(),
        ];
    }
}
