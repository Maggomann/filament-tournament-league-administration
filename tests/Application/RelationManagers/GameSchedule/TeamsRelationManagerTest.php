
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use function Pest\Livewire\livewire;

it('can attach a team', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();

    livewire(GameScheduleResource\RelationManagers\TeamsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        AttachAction::class,
        null,
        [
            'recordId' => $team->id,
        ],
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($team) {
            $this->assertDatabaseHas('game_schedule_team', [
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $team->id,
            ]);
        }
    );
});

it('can detach a team', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();

    $gameSchedule->teams()->sync([$team->id]);

    livewire(GameScheduleResource\RelationManagers\TeamsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        DetachAction::class,
        $team,
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) {
            $this->assertCount(0, $gameSchedule->teams);
        }
    );
});

it('can sync all teams', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $teamOne = TeamFactory::new()->for($gameSchedule->league)->create();
    $teamTwo = TeamFactory::new()->for($gameSchedule->league)->create();
    $teamThree = TeamFactory::new()->for($gameSchedule->league)->create();

    livewire(GameScheduleResource\RelationManagers\TeamsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        'syncAllTeams',
        null,
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($teamOne, $teamTwo, $teamThree) {
            $this->assertDatabaseHas('game_schedule_team', [
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $teamOne->id,
            ]);
            $this->assertDatabaseHas('game_schedule_team', [
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $teamTwo->id,
            ]);
            $this->assertDatabaseHas('game_schedule_team', [
                'game_schedule_id' => $gameSchedule->id,
                'team_id' => $teamThree->id,
            ]);
        }
    );
});

it('can bulk detach teams', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $teams = TeamFactory::new()->for($gameSchedule->league)->times(10)->create();
    $gameSchedule->teams()->sync($teams->pluck('id'));

    livewire(GameScheduleResource\RelationManagers\TeamsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableBulkAction(DetachBulkAction::class, $teams)
        ->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) {
            $this->assertCount(0, $gameSchedule->teams);
            $this->assertCount(10, Team::all());
        }
    );
});
