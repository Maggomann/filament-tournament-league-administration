<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Database\Factories\TotalTeamPointFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\FirstOrCreateTotalTeamPointAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;

it('creates new total team point classes', function () {
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

    $homeTotalTeamPoint = app(FirstOrCreateTotalTeamPointAction::class)->execute($homeTeam, $gameSchedule);
    $guestTotalTeamPoint = app(FirstOrCreateTotalTeamPointAction::class)->execute($guestTeam, $gameSchedule);

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($homeTotalTeamPoint, $guestTotalTeamPoint, $homeTeam, $guestTeam) {
            $this->assertCount(2, $gameSchedule->totalTeamPoints);
            $this->assertDatabaseHas(TotalTeamPoint::class, [
                'id' => $homeTotalTeamPoint->id,
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $homeTeam->id,
            ]);
            $this->assertDatabaseHas(TotalTeamPoint::class, [
                'id' => $guestTotalTeamPoint->id,
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $guestTeam->id,
            ]);
        }
    );
});

it('uses the existing total team point classes', function () {
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

    $homeTotalTeamPoint = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->for($homeTeam)
        ->create();

    $guestTotalTeamPoint = TotalTeamPointFactory::new()
        ->for($gameSchedule)
        ->for($guestTeam)
        ->create();

    $homeTotalTeamPointAction = app(FirstOrCreateTotalTeamPointAction::class)->execute($homeTeam, $gameSchedule);
    $guestTotalTeamPointAction = app(FirstOrCreateTotalTeamPointAction::class)->execute($guestTeam, $gameSchedule);

    tap(
        $gameSchedule->refresh(),
        fn (GameSchedule $gameSchedule) => $this->assertCount(2, $gameSchedule->totalTeamPoints)
    );

    tap(
        $homeTotalTeamPoint->refresh(),
        fn (TotalTeamPoint $homeTotalTeamPoint) => $this->assertSame($homeTotalTeamPoint->id, $homeTotalTeamPointAction->id)
    );

    tap(
        $guestTotalTeamPoint->refresh(),
        fn (TotalTeamPoint $guestTotalTeamPoint) => $this->assertSame($guestTotalTeamPoint->id, $guestTotalTeamPointAction->id)
    );
});
