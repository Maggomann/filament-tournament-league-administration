<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule>
 */
class GameScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule>
     */
    protected $model = GameSchedule::class;

    public function definition(): array
    {
        return [
            'federation_id' => FederationFactory::new()->lazy(),
            'gameschedulable_type' => (new League())->getMorphClass(),
            'gameschedulable_id' => LeagueFactory::new()->lazy(),
            'name' => $this->faker->words(2, true),
            'started_at' => now(),
            'period_end' => now()->addWeeks(4),
        ];
    }
}
