
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\DeleteGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateOrCreateGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\DTO\GameScheduleData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages\ListGameSchedules;
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

it('can validate input for game schedule page', function () {
    livewire(GameScheduleResource\Pages\CreateGameSchedule::class)
        ->fillForm([
            'name' => null,
            'federation_id' => null,
            'league_id' => null,
            'started_at' => null,
            'ended_at' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'federation_id' => 'required',
            'league_id' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required',
        ]);
});

it('player create game schedule should receive execute from UpdateOrCreatePlayerAction', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $startedAt = now()->toDateTimeString();
    $endedAt = now()->addHour()->toDateTimeString();

    $mock = $this->mock(UpdateOrCreateGameScheduleAction::class);
    $mock->shouldReceive('execute')
        ->with(GameScheduleData::class)
        ->once()
        ->andReturn(GameSchedule::class);

    $createGameSchedule = new GameScheduleResource\Pages\CreateGameSchedule();
    $createGameSchedule->form->fill([
        'name' => 'Example',
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'started_at' => $startedAt,
        'ended_at' => $endedAt,
        'game_days' => 5,
    ]);
    $createGameSchedule->create();
});

it('can save a game schedule', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $startedAt = now()->toDateTimeString();
    $endedAt = now()->addHour()->toDateTimeString();

    livewire(GameScheduleResource\Pages\EditGameSchedule::class, [
        'record' => $gameSchedule->getRouteKey(),
    ])
    ->fillForm([
        'name' => 'Example Edit',
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'started_at' => $startedAt,
        'ended_at' => $endedAt,
    ])
    ->call('save')
    ->assertHasNoFormErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($federation, $league, $startedAt, $endedAt) {
            expect($gameSchedule)
                ->name->toBe('Example Edit')
                ->federation_id->toBe($federation->id)
                ->league_id->toBe($league->id);

            $this->assertDatabaseHas(GameSchedule::class, [
                'id' => $gameSchedule->id,
                'name' => 'Example Edit',
                'federation_id' => $federation->id,
                'league_id' => $league->id,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);

            $this->assertCount(0, $gameSchedule->days);
        }
    );
});

it('game schedule edit page should receive execute from UpdateOrCreateTeamAction', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    $mock = $this->mock(UpdateOrCreateGameScheduleAction::class);
    $mock->shouldReceive('execute')
        ->with(GameScheduleData::class, GameSchedule::class)
        ->once()
        ->andReturn(GameSchedule::class);

    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $startedAt = now()->toDateTimeString();
    $endedAt = now()->addHour()->toDateTimeString();

    $editGameSchedule = new GameScheduleResource\Pages\EditGameSchedule();
    $editGameSchedule->record = $gameSchedule;
    $editGameSchedule->form->fill([
        'name' => 'Example',
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'started_at' => $startedAt,
        'ended_at' => $endedAt,
    ]);
    $editGameSchedule->save();
});

dataset('inputForValidateGameSchedulePage', function () {
    yield 'The start date must not be outside the time periods of the assigned days.' => [
        'fluent' => fn () => new Fluent([
            'name' => 'Example',
            'started_at' => '2022-01-16 00:00:00',
            'ended_at' => '2022-01-19 00:00:00',
            'actionErrors' => null,
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'The End date must not be outside the time periods of the assigned days.' => [
        'fluent' => fn () => new Fluent([
            'name' => 'Example',
            'started_at' => '2022-01-09 00:00:00',
            'ended_at' => '2022-01-15 00:00:00',
            'actionErrors' => null,
            'actionErrors' => [
                'ended_at',
            ],
        ]),
    ];
    yield 'the current start date must not be greater than the current end date' => [
        'fluent' => fn () => new Fluent([
            'day' => 3,
            'started_at' => '2022-01-15 00:00:00',
            'ended_at' => '2022-01-14 00:00:00',
            'actionErrors' => [
                'started_at',
                'ended_at',
            ],
        ]),
    ];
    yield 'the current end date must not be smaller than the current start date' => [
        'fluent' => fn () => new Fluent([
            'day' => 3,
            'started_at' => '2022-01-15 00:00:00',
            'ended_at' => '2022-01-14 00:00:00',
            'actionErrors' => [
                'started_at',
                'ended_at',
            ],
        ]),
    ];
    yield fn () => new Fluent([
        'name' => 'Example',
        'started_at' => '2022-01-09 00:00:00',
        'ended_at' => '2022-01-21 00:00:00',
        'actionErrors' => null,
    ]);
    yield fn () => new Fluent([
        'name' => 'Example',
        'started_at' => '2022-01-13 00:00:00',
        'ended_at' => '2022-01-18 00:00:00',
        'actionErrors' => null,
    ]);
});

it('can valiadate input for game schedule page', function (Fluent $input) {
    $federation = FederationFactory::new()->create();

    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);

    GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => '2022-01-14 00:00:00',
            'ended_at' => '2022-01-17 23:59:59',
            'day' => 1,
        ]);

    $livewire = livewire(GameScheduleResource\Pages\EditGameSchedule::class, [
        'record' => $gameSchedule->getRouteKey(),
    ])
    ->fillForm([
        'name' => 'Example Edit',
        'federation_id' => $federation->id,
        'league_id' => $gameSchedule->league->id,
        'started_at' => $input->started_at,
        'ended_at' => $input->ended_at,
    ])
    ->call('save');

    if ($input->actionErrors) {
        $livewire->assertHasFormErrors($input->actionErrors);

        return;
    }

    $livewire->assertHasNoFormErrors();
})->with('inputForValidateGameSchedulePage');

it('can delete a game schedule', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    livewire(GameScheduleResource\Pages\EditGameSchedule::class, [
        'record' => $gameSchedule->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($gameSchedule);
});

it('use the DeleteGameScheduleAction', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create();

    $mock = $this->mock(DeleteGameScheduleAction::class);
    $mock->shouldReceive('execute')
        ->with(GameSchedule::class)
        ->once();

    livewire(ListGameSchedules::class)
        ->callTableAction(DeleteAction::class, $gameSchedule);
});
