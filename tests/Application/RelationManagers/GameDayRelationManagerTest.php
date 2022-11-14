
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameDayFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\ViewAction;
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
        'record' => $gameDay,
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

it('can edit edit a game day', function () {
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
        'record' => $gameDay,
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

// TODO: FORM DATES | DATE
// TODO: DELETE
// TODO: Check action execute
