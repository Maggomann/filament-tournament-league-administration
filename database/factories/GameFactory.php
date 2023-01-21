<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\FilamentTournamentLeagueAdministration\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\FilamentTournamentLeagueAdministration\Models\Game>
     */
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'game_schedule_id' => function (array $attributes) {
                $federation = FederationFactory::new()->create();

                return GameScheduleFactory::new()
                    ->for($federation)
                    ->for(LeagueFactory::new()->for($federation))
                    ->create([
                        'started_at' => '2022-01-10 00:00:00',
                        'ended_at' => '2022-01-20 00:00:00',
                    ]);
            },
            'game_day_id' => function (array $attributes) {
                return GameDayFactory::new()->create([
                    'game_schedule_id' => $attributes['game_schedule_id'],
                    'started_at' => '2022-01-12 00:00:00',
                    'ended_at' => '2022-01-12 23:59:59',
                    'day' => 2,
                ]);
            },
            'home_team_id' => TeamFactory::new()->lazy(),
            'guest_team_id' => TeamFactory::new()->lazy(),
            'home_points_legs' => $this->faker->numberBetween($min = 1, $max = 200),
            'guest_points_legs' => $this->faker->numberBetween($min = 1, $max = 200),
            'home_points_games' => $this->faker->numberBetween($min = 1, $max = 200),
            'guest_points_games' => $this->faker->numberBetween($min = 1, $max = 200),
            'has_an_overtime' => $this->faker->boolean,
            'home_points_after_draw' => function (array $attributes) {
                return ($attributes['has_an_overtime'])
                    ? $this->faker->numberBetween($min = 1, $max = 200)
                    : 0;
            },
            'guest_points_after_draw' => function (array $attributes) {
                return ($attributes['has_an_overtime'])
                    ? $this->faker->numberBetween($min = 1, $max = 200)
                    : 0;
            },
            'started_at' => now(),
            'ended_at' => now()->addHour(),
        ];
    }

    public function withTeams(): self
    {
        return $this->afterCreating(
            function (Game $game) {
                $game->homeTeam->gameSchedules()->save($game->gameSchedule);
                $game->guestTeam->gameSchedules()->save($game->gameSchedule);
            }
        );
    }
}
