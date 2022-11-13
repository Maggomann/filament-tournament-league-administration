<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\Player>
     */
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique(reset: true)->email,
            'slug' => null,
        ];
    }

    public function withPlausibleBelongsToRelations(array $parameters = []): self
    {
        return $this->afterCreating(
            function (Player $player) use ($parameters) {
                $team = TeamFactory::new()
                    ->withPlausibleRelations($parameters)
                    ->create($parameters);

                $player->team()->associate($team);
                $player->save();
            }
        );
    }
}
