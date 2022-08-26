<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\League>
 */
class LeagueFactory extends Factory
{
    /** @var string */
    protected $model = League::class;

    /** @return array<string> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => null,
        ];
    }
}
