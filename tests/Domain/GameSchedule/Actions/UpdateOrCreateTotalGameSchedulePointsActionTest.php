<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Database\Factories\TotalTeamPointFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\FirstOrCreateTotalTeamPointAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateTeamPlacementsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateTotalTeamPointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\TotalTeamPointData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateOrCreateTotalGameSchedulePointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

test('all required ation classes are called', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    GameDayFactory::new()
        ->for($gameSchedule)
        ->create();

    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    GameFactory::new()
        ->for($gameSchedule)
        ->for(GameDayFactory::new()->for($gameSchedule))
        ->for($homeTeam, 'homeTeam')
        ->for($guestTeam, 'guestTeam')
        ->create();

    $mockFirstOrCreateTotalTeamPointAction = $this->mock(FirstOrCreateTotalTeamPointAction::class);
    $mockUpdateTotalTeamPointsAction = $this->mock(UpdateTotalTeamPointsAction::class);

    $gameSchedule->teams->each(function (Team $team) use (
        $gameSchedule,
        $mockFirstOrCreateTotalTeamPointAction,
        $mockUpdateTotalTeamPointsAction
    ) {
        $totalTeamPoint = TotalTeamPointFactory::new()
            ->for($gameSchedule)
            ->for($team)
            ->create();

        $mockFirstOrCreateTotalTeamPointAction->shouldReceive('execute')
            ->with(Team::class, GameSchedule::class)
            ->once()
            ->andReturn($totalTeamPoint);

        $mockUpdateTotalTeamPointsAction->shouldReceive('execute')
            ->with(TotalTeamPoint::class, TotalTeamPointData::class)
            ->once();
    });

    $mock = $this->mock(UpdateTeamPlacementsAction::class);
    $mock->shouldReceive('execute')
        ->with(GameSchedule::class)
        ->once();

    app(UpdateOrCreateTotalGameSchedulePointsAction::class)->execute($gameSchedule);
});
