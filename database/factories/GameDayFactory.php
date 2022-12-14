<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay>
 */
class GameDayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay>
     */
    protected $model = GameDay::class;

    public function definition(): array
    {
        return [
            'game_schedule_id' => GameScheduleFactory::new()->lazy(),
            'day' => $this->faker->numberBetween($min = 1, $max = 10),
            'started_at' => now(),
            'ended_at' => now()->addDay(),
        ];
    }
}
