<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\DeleteGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

it('can delete a game schedule with his relations', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    $gameDay = GameDayFactory::new()
        ->for($gameSchedule)
        ->create();

    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    $game = GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create();

    $playerOne = PlayerFactory::new()->for($homeTeam)->create();
    $playerOne->gameSchedules()->save($gameSchedule);
    $playerTwo = PlayerFactory::new()->for($guestTeam)->create();
    $playerTwo->gameSchedules()->save($gameSchedule);

    app(DeleteGameScheduleAction::class)->execute($gameSchedule);

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($gameDay, $homeTeam, $guestTeam, $game, $playerOne, $playerTwo) {
            $this->assertSoftDeleted($gameSchedule);
            $this->assertSoftDeleted($gameDay);
            $this->assertSoftDeleted($game);
            $this->assertCount(0, $gameSchedule->teams);
            $this->assertCount(0, $gameSchedule->players);

            $this->assertNotSoftDeleted($homeTeam);
            $this->assertNotSoftDeleted($guestTeam);
            $this->assertNotSoftDeleted($playerOne);
            $this->assertNotSoftDeleted($playerTwo);

            $gameSchedule
                ->totalTeamPoints
                ->each(fn (TotalTeamPoint $totalTeamPoint) => $this->assertSoftDeleted($totalTeamPoint));
        }
    );
});
