<?php

namespace Database\Factories\FilamentTournamentLeagueAdministration;

use App\League;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'slug' => $this->faker->slug,
        ];
    }
}
