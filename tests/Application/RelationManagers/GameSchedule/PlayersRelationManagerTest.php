<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use function Pest\Livewire\livewire;

it('can attach a player', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $player = PlayerFactory::new()->for($team)->create();

    livewire(GameScheduleResource\RelationManagers\PlayersRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        AttachAction::class,
        null,
        [
            'recordId' => $player->id,
        ],
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($player) {
            $this->assertDatabaseHas('game_schedule_player', [
                'game_schedule_id' => $gameSchedule->id,
                'player_id' => $player->id,
            ]);
        }
    );
});

it('can detach a player', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $player = PlayerFactory::new()->for($team)->create();
    $gameSchedule->players()->sync([$player->id]);

    livewire(GameScheduleResource\RelationManagers\PlayersRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        DetachAction::class,
        $player,
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) {
            $this->assertCount(0, $gameSchedule->players);
        }
    );
});

it('can sync all players', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $playerOne = PlayerFactory::new()->for($team)->create();
    $playerTwo = PlayerFactory::new()->for($team)->create();
    $playerThree = PlayerFactory::new()->for($team)->create();

    livewire(GameScheduleResource\RelationManagers\PlayersRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableAction(
        'syncAllPlayers',
        null,
    )->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) use ($playerOne, $playerTwo, $playerThree) {
            $this->assertDatabaseHas('game_schedule_player', [
                'game_schedule_id' => $gameSchedule->id,
                'player_id' => $playerOne->id,
            ]);
            $this->assertDatabaseHas('game_schedule_player', [
                'game_schedule_id' => $gameSchedule->id,
                'player_id' => $playerTwo->id,
            ]);
            $this->assertDatabaseHas('game_schedule_player', [
                'game_schedule_id' => $gameSchedule->id,
                'player_id' => $playerThree->id,
            ]);
        }
    );
});

it('can bulk detach players', function () {
    $federation = FederationFactory::new()->create();
    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for(LeagueFactory::new()->for($federation))
        ->create([
            'started_at' => '2022-01-10 00:00:00',
            'ended_at' => '2022-01-20 00:00:00',
        ]);
    $team = TeamFactory::new()->for($gameSchedule->league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $players = PlayerFactory::new()->for($team)->times(10)->create();
    $gameSchedule->players()->sync($players->pluck('id'));

    livewire(GameScheduleResource\RelationManagers\PlayersRelationManager::class, [
        'ownerRecord' => $gameSchedule,
    ])->callTableBulkAction(DetachBulkAction::class, $players)
        ->assertHasNoTableActionErrors();

    tap(
        $gameSchedule->refresh(),
        function (GameSchedule $gameSchedule) {
            $this->assertCount(0, $gameSchedule->players);
            $this->assertCount(10, Player::all());
        }
    );
});
