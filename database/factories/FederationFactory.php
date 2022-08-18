<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

class FederationFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Federation::class;

    public function definition(): array
    {
        return [
            'title' => $title = $this->faker->unique()->sentence(4),
        ];
    }
}
