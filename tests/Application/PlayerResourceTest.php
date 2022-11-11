
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('can render player list table', function () {
    $this->get(PlayerResource::getUrl('index'))->assertSuccessful();
});

it('can render player create form', function () {
    $this->get(PlayerResource::getUrl('create'))->assertSuccessful();
});

it('can render player edit form', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();
    $team = TeamFactory::new()->for($league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $player = PlayerFactory::new()
        ->for($team)
        ->create();

    $this->get(PlayerResource::getUrl('edit', [
        'record' => $player,
    ]))->assertSuccessful();
});
