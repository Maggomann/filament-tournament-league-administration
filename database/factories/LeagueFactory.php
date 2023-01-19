<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\League>
 */
class LeagueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\League>
     */
    protected $model = League::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => null,
            'upload_image' => $this->faker->imageUrl(width: 50, height: 50),
        ];
    }
}
