<?php

namespace Database\Seeders;

use Database\Factories\GameDayFactory;
use Database\Factories\GameScheduleFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;

class GameSchedulesTableSeeder extends Seeder
{
    protected ?GameSchedule $gameSchedule = null;

    public function run(): void
    {
        Federation::with([
            'leagues.teams',
            'leagues.players',
        ])
            ->get()
            ->each(function (Federation $federation) {
                $federation->leagues
                    ->each(function (League $league) use ($federation) {
                        $this->createGameSchedule($federation, $league)
                            ->createGameDays()
                            ->syncTeams($league)
                            ->syncPlayers($league);
                    });
            });
    }

    private function createGameSchedule(Federation $federation, League $league): self
    {
        $this->gameSchedule = GameScheduleFactory::new()
            ->for($league)
            ->for($federation)
            ->create();

        return $this;
    }

    private function createGameDays(): self
    {
        for ($day = 1; $day < 11; $day++) {
            GameDayFactory::new()
                ->for($this->gameSchedule)
                ->create([
                    'day' => $day,
                    'started_at' => now()->addDays($day),
                    'ended_at' => now()->addDays($day + 1),
                ]);
        }

        return $this;
    }

    private function syncTeams(League $league): self
    {
        $this->gameSchedule->teams()->sync($league->teams()->pluck('id'));

        return $this;
    }

    private function syncPlayers(League $league): self
    {
        $this->gameSchedule->players()->sync($league->players()->pluck('tournament_league_players.id'));

        return $this;
    }
}
