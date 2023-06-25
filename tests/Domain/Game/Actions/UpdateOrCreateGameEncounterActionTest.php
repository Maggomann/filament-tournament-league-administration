<?php

use Database\Factories\GameEncounterFactory;
use Database\Factories\GameFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateOrCreateGameEncounterAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameEncounterData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;

dataset('UpdateOrCreateGameEncounters', function () {
    yield fn () => GameEncounterData::create([
        'game_id' => ($game = GameFactory::new()->create()) ? $game->id : 1,
        'game_encounter_type_id' => 1,
        'order' => 1,
        'home_team_win' => 2,
        'home_team_defeat' => 1,
        'guest_team_win' => 1,
        'guest_team_defeat' => 2,
        'home_team_points_leg' => 1,
        'guest_team_points_leg' => 0,
        'home_player_id_1' => 1,
        'home_player_id_2' => 2,
        'guest_player_id_1' => 3,
        'guest_player_id_2' => 4,
    ]);

    yield fn () => GameEncounterData::create([
        'game_id' => ($game = GameFactory::new()->create()) ? $game->id : 1,
        'game_encounter_type_id' => 1,
        'order' => 1,
        'home_team_win' => 1,
        'home_team_defeat' => 2,
        'guest_team_win' => 2,
        'guest_team_defeat' => 1,
        'home_team_points_leg' => 0,
        'guest_team_points_leg' => 1,
        'home_player_id_1' => 1,
        'home_player_id_2' => 2,
        'guest_player_id_1' => 3,
        'guest_player_id_2' => 4,
    ]);
});

it('creates an game encounter', function (GameEncounterData $gameEncounterData) {
    $gameEncounter = app(UpdateOrCreateGameEncounterAction::class)->execute($gameEncounterData);

    if (env('DB_CONNECTION') === 'sqlite') {
        $this->assertDatabaseHas(GameEncounter::class, $gameEncounter->getAttributes());

        return;
    }

    $this->assertDatabaseHas(GameEncounter::class, $gameEncounter->attributesToArray());
})->with('UpdateOrCreateGameEncounters');

it('updates an game', function (GameEncounterData $gameEncounterData) {
    $gameEncounter = app(UpdateOrCreateGameEncounterAction::class)
        ->execute(
            $gameEncounterData,
            GameEncounterFactory::new()->create([
                'game_id' => 2,
                'game_encounter_type_id' => 2,
                'order' => 0,
                'home_team_win' => 3,
            ])
        );

    if (env('DB_CONNECTION') === 'sqlite') {
        $this->assertDatabaseHas(GameEncounter::class, $gameEncounter->getAttributes());

        return;
    }

    $this->assertDatabaseHas(GameEncounter::class, $gameEncounter->attributesToArray());
    $this->assertSame($gameEncounter->game_id, $gameEncounterData->game_id);
    $this->assertSame($gameEncounter->game_encounter_type_id, $gameEncounterData->game_encounter_type_id);
    $this->assertSame($gameEncounter->order, $gameEncounterData->order);
    $this->assertSame($gameEncounter->home_team_win, $gameEncounterData->home_team_win);
})->with('UpdateOrCreateGameEncounters');
