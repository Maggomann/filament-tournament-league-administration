<?php

namespace Database\Seeders;

use Database\Factories\GameFactory;
use Illuminate\Database\Seeder;
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
            ->each(fn (GameSchedule $gameSchedule) => $gameSchedule->teams->each(fn (Team $firstTeam) => collect([$firstTeam->id])
                        ->crossJoin($gameSchedule->teams->whereNotIn('id', [$firstTeam->id])->pluck('id'))
                        ->each(fn ($crossJoinTeams) => GameFactory::new()
                                ->for($gameSchedule, 'gameSchedule')
                                ->for($gameSchedule->days->shuffle()->first(), 'gameDay')
                                ->create([
                                    'home_team_id' => $crossJoinTeams[0],
                                    'guest_team_id' => $crossJoinTeams[1],
                                ])
                        )
                )
            );
    }
}
