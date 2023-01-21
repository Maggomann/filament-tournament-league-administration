<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament>
 */
class FreeTournamentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament>
     */
    protected $model = FreeTournament::class;

    public function definition(): array
    {
        return [
            'mode_id' => $this->faker->numberBetween($min = 1, $max = 9),
            'dart_type_id' => $this->faker->numberBetween($min = 1, $max = 2),
            'qualification_level_id' => $this->faker->numberBetween($min = 1, $max = 7),
            'name' => $this->faker->name,
            'slug' => null,
            'description' => $this->faker->text,
            'maximum_number_of_participants' => $this->faker->numberBetween($min = 1, $max = 10),
            'coin_money' => $this->faker->numberBetween($min = 20, $max = 250),
            'prize_money_depending_on_placement' => [
                '1. Platz' => 'Test',
            ],
            'upload_image' => $this->faker->imageUrl(width: 50, height: 50),
            'started_at' => now(),
            'ended_at' => now()->addHour(),
        ];
    }
}
