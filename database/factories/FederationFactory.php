<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Federation>
 */
class FederationFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Federation::class;

    public function definition(): array
    {
        return [
            'calculation_type' => $this->faker->randomElement([1, 2]),
            'name' => $this->faker->sentence(2),
        ];
    }
}
