<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\CreateAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use function Pest\Livewire\livewire;

it('can show a game day', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => now()->subHours(5)->toDateTimeString(),
            'ended_at' => now()->addHours(10)->toDateTimeString(),
        ]);

    $gameDay = GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => now()->subHours(4)->toDateTimeString(),
            'ended_at' => now()->addHours(9)->toDateTimeString(),
        ]);

    livewire(GameScheduleResource\RelationManagers\GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->callTableAction(
        ViewAction::class,
        $gameDay,
        data: [
            'game_schedule_id' => $gameSchedule->id,
            'day' => $gameDay->day,
            'started_at' => $gameDay->started_at->toDateTimeString(),
            'ended_at' => $gameDay->ended_at->toDateTimeString(),
        ],
    )
    ->assertHasNoTableActionErrors();
});

it('can create a game day', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => now()->subHours(5)->toDateTimeString(),
            'ended_at' => now()->addHours(10)->toDateTimeString(),
        ]);

    $startedAt = now()->addHours(5)->toDateTimeString();
    $endedAt = now()->addHours(9)->toDateTimeString();

    livewire(GameScheduleResource\RelationManagers\GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->callTableAction(
        CreateAction::class,
        null,
        data: [
            'game_schedule_id' => $gameSchedule->id,
            'day' => 1,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ],
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->days()->first(),
        function (GameDay $gameDay) use ($gameSchedule, $startedAt, $endedAt) {
            expect($gameDay)
                ->game_schedule_id->toBe($gameSchedule->id)
                ->day->toBe(1);

            $this->assertDatabaseHas(GameDay::class, [
                'id' => $gameDay->id,
                'game_schedule_id' => $gameSchedule->id,
                'day' => 1,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);
        }
    );
});

it('can edit a game day', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => now()->subHours(5)->toDateTimeString(),
            'ended_at' => now()->addHours(10)->toDateTimeString(),
        ]);

    $gameDay = GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => now()->subHours(2)->toDateTimeString(),
            'ended_at' => now()->addHours(3)->toDateTimeString(),
            'day' => 1,
        ]);

    $startedAt = now()->addHours(5)->toDateTimeString();
    $endedAt = now()->addHours(9)->toDateTimeString();

    livewire(GameScheduleResource\RelationManagers\GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->callTableAction(
        EditAction::class,
        $gameDay,
        data: [
            'game_schedule_id' => $gameSchedule->id,
            'day' => 2,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ],
    )->assertHasNoTableActionErrors();

    tap(
        $gameDay->refresh(),
        function (GameDay $gameDay) use ($gameSchedule, $startedAt, $endedAt) {
            expect($gameDay)
                ->game_schedule_id->toBe($gameSchedule->id)
                ->day->toBe(2);

            $this->assertDatabaseHas(GameDay::class, [
                'id' => $gameDay->id,
                'game_schedule_id' => $gameSchedule->id,
                'day' => 2,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);
        }
    );
});

dataset('inputForValidateGameDayPage', function () {
    yield 'the start date of the current day must not be smaller than the start date of the game schedule' => [
        'fluent' => fn () => new Fluent([
            'day' => 1,
            'started_at' => '2022-01-09 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'the start date of the current day must not be same than the end date of the game schedule' => [
        'fluent' => fn () => new Fluent([
            'day' => 5,
            'started_at' => '2022-01-20 00:00:00',
            'ended_at' => '2022-01-21 00:00:00',
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'the start date of the current day must not be greater than the end date of the game schedule' => [
        'fluent' => fn () => new Fluent([
            'day' => 5,
            'started_at' => '2022-01-21 00:00:00',
            'ended_at' => '2022-01-22 00:00:00',
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'the end date of the current day must not be greater than the end date of the game schedule' => [
        'fluent' => fn () => new Fluent([
            'day' => 1,
            'started_at' => '2022-01-11 00:00:00',
            'ended_at' => '2022-01-21 00:00:00',
            'actionErrors' => [
                'ended_at',
            ],
        ]),
    ];
    yield 'the end date of the current day must not be greater than the start date of the following day' => [
        'fluent' => fn () => new Fluent([
            'day' => 1,
            'started_at' => '2022-01-11 00:00:00',
            'ended_at' => '2022-01-12 00:00:00',
            'actionErrors' => [
                'ended_at',
            ],
        ]),
    ];
    yield 'the end date of the current day must not be smaller than the start date of the day' => [
        'fluent' => fn () => new Fluent([
            'day' => 1,
            'started_at' => '2022-01-11 10:00:00',
            'ended_at' => '2022-01-11 09:00:00',
            'actionErrors' => [
                'ended_at',
            ],
        ]),
    ];
    yield 'the start date of the current day must not be smaller than the end date of the previous day' => [
        'fluent' => fn () => new Fluent([
            'day' => 3,
            'started_at' => '2022-01-12 23:59:59',
            'ended_at' => '2022-01-13 23:59:59',
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'the end date of the current day must not be greater than the start date of the following day' => [
        'fluent' => fn () => new Fluent([
            'day' => 3,
            'started_at' => '2022-01-13 00:00:00',
            'ended_at' => '2022-01-14 10:00:00',
            'actionErrors' => [
                'ended_at',
            ],
        ]),
    ];
    yield 'the start date of the current day must not be smaller than the end date of the previous day' => [
        'fluent' => fn () => new Fluent([
            'day' => 5,
            'started_at' => '2022-01-04 23:00:00',
            'ended_at' => '2022-01-05 10:00:00',
            'actionErrors' => [
                'started_at',
            ],
        ]),
    ];
    yield 'the same day may appear only once' => [
        'fluent' => fn () => new Fluent([
            'started_at' => '2022-01-12 00:00:00',
            'ended_at' => '2022-01-12 23:59:59',
            'day' => 2,
            'actionErrors' => [
                'day',
            ],
        ]),
    ];
    yield fn () => new Fluent([
        'day' => 1,
        'started_at' => '2022-01-11 00:00:00',
        'ended_at' => '2022-01-11 23:59:59',
        'actionErrors' => null,
    ]);
    yield fn () => new Fluent([
        'day' => 3,
        'started_at' => '2022-01-13 00:00:00',
        'ended_at' => '2022-01-13 23:59:59',
        'actionErrors' => null,
    ]);
    yield fn () => new Fluent([
        'day' => 5,
        'started_at' => '2022-01-15 00:00:00',
        'ended_at' => '2022-01-15 23:59:59',
        'actionErrors' => null,
    ]);
});

it('can valiadate input for game day page', function (Fluent $input) {
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
            'started_at' => '2022-01-12 00:00:00',
            'ended_at' => '2022-01-12 23:59:59',
            'day' => 2,
        ]);

    GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => '2022-01-14 00:00:00',
            'ended_at' => '2022-01-14 23:59:59',
            'day' => 4,
        ]);

    $gameDay = GameDayFactory::new()
        ->for($gameSchedule)
        ->create();

    $livewire = livewire(GameScheduleResource\RelationManagers\GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])
    ->callTableAction(
        EditAction::class,
        $gameDay,
        data: [
            'game_schedule_id' => $gameSchedule->id,
            'day' => $input->day,
            'started_at' => $input->started_at,
            'ended_at' => $input->ended_at,
        ],
    );

    if ($input->actionErrors) {
        $livewire->assertHasTableActionErrors($input->actionErrors);

        return;
    }

    $livewire->assertHasNoTableActionErrors();
})->with('inputForValidateGameDayPage');

// TODO: Check action execute

it('can delete a game schedule', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);

    $gameDay = GameDayFactory::new()
        ->for($gameSchedule)
        ->create([
            'started_at' => '2022-01-12 00:00:00',
            'ended_at' => '2022-01-12 23:59:59',
            'day' => 2,
        ]);

    livewire(GameScheduleResource\RelationManagers\GameDaysRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(DeleteAction::class, $gameDay);

    $this->assertSoftDeleted($gameDay);
});
