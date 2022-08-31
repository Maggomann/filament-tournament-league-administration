<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Player>
 */
class PlayerFactory extends Factory
{
    /** @var string */
    protected $model = Player::class;

    /** @return array<string> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique(reset: true)->email,
            'slug' => null,
        ];
    }
}
