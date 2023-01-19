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
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\Federation>
     */
    protected $model = Federation::class;

    public function definition(): array
    {
        return [
            'calculation_type_id' => $this->faker->randomElement([1, 2]),
            'name' => $this->faker->company,
            'upload_image' => $this->faker->imageUrl(width: 50, height: 50),
        ];
    }
}
