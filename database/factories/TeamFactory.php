<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Team>
 */
class TeamFactory extends Factory
{
    /** @var string */
    protected $model = Team::class;

    /** @return array<string> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => null,
        ];
    }
}
