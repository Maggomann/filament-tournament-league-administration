
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\GameDaysRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\GamesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\PlayersRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\TeamsRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\TotalTeamPointsRelationManager;
use function Pest\Livewire\livewire;

it('can render game schedule list table', function () {
    $this->get(GameScheduleResource::getUrl('index'))->assertSuccessful();
});

it('can render game schedule create form', function () {
    $this->get(GameScheduleResource::getUrl('create'))->assertSuccessful();
});

it('can render game schedule edit form', function () {
    $federation = FederationFactory::new()->create();

    $this->get(GameScheduleResource::getUrl('edit', [
        'record' => GameScheduleFactory::new()
            ->for($federation)
            ->for(LeagueFactory::new()->for($federation))
            ->create(),
    ]))->assertSuccessful();
});

it('can render all gameSchedule relation managers', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    livewire(GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($gameSchedule->days);

    livewire(TeamsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($gameSchedule->teams);

    livewire(PlayersRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($gameSchedule->players);

    livewire(GamesRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
        ->assertSuccessful();

    livewire(TotalTeamPointsRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($gameSchedule->totalTeamPoints);
});

it('can create a game schedule', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $startedAt = now()->toDateTimeString();
    $endedAt = now()->addHour()->toDateTimeString();

    livewire(GameScheduleResource\Pages\CreateGameSchedule::class)
        ->fillForm([
            'name' => 'Example',
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'game_days' => 5,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    tap(
        GameSchedule::where([
            'name' => 'Example',
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ])->first(),
        function (GameSchedule $gameSchedule) use ($federation, $league, $startedAt, $endedAt) {
            $this->assertDatabaseHas(GameSchedule::class, [
                'id' => $gameSchedule->id,
                'name' => 'Example',
                'federation_id' => $federation->id,
                'league_id' => $league->id,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);
            $this->assertCount(5, $gameSchedule->days);
        }
    );
});
