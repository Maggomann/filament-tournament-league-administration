<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\Team>
     */
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => null,
        ];
    }

    public function withPlausibleRelations(array $parameters = []): self
    {
        return $this->afterCreating(
            function (Team $team) use ($parameters) {
                $federation = FederationFactory::new()->create($parameters);

                $gameSchedule = GameScheduleFactory::new()
                    ->for($federation)
                    ->for(LeagueFactory::new()->for($federation))
                    ->create($parameters);

                $team->league()->associate($gameSchedule->league);
                $team->save();

                $team->gameSchedules()->save($gameSchedule);
            }
        );
    }
}
