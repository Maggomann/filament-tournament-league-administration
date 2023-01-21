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
            'upload_image' => $this->faker->imageUrl(width: 50, height: 50),
        ];
    }

    public function withPlausibleBelongsToRelations(): self
    {
        return $this->afterCreating(
            function (Player $player) {
                $team = TeamFactory::new()
                    ->withPlausibleRelations()
                    ->create();

                $player->team()->associate($team);
                $player->save();
            }
        );
    }
}
