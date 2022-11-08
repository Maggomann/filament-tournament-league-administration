<?php

namespace Database\Seeders;

use Database\Factories\GameFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateOrCreateTotalGameSchedulePointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;

class GamesTableSeeder extends Seeder
{
    protected ?Game $game = null;

    public function run(): void
    {
        GameSchedule::with([
            'teams',
            'days',
        ])
            ->get()
            ->each(function (GameSchedule $gameSchedule) {
                $gameSchedule->teams->each(fn (Team $firstTeam) => collect([$firstTeam->id])
                    ->crossJoin($gameSchedule->teams->whereNotIn('id', [$firstTeam->id])->pluck('id'))
                    ->each(fn ($crossJoinTeams) => GameFactory::new()
                            ->for($gameSchedule, 'gameSchedule')
                            ->for($gameDay = $gameSchedule->days->shuffle()->first(), 'gameDay')
                            ->create([
                                'home_team_id' => $crossJoinTeams[0],
                                'guest_team_id' => $crossJoinTeams[1],
                                'has_an_overtime' => false,
                                'started_at' => $gameDay->started_at->addHours(
                                    random_int(1, 11)
                                ),
                                'ended_at' => $gameDay->ended_at->subHours(
                                    random_int(1, 11)
                                ),
                            ])
                    )
                );

                app(UpdateOrCreateTotalGameSchedulePointsAction::class)->execute($gameSchedule);
            });
    }
}
