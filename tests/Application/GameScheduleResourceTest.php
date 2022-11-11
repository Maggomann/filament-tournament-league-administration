
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('can render game schedule list table', function () {
    $this->get(GameScheduleResource::getUrl('index'))->assertSuccessful();
});

it('can render game schedule create form', function () {
    $this->get(GameScheduleResource::getUrl('create'))->assertSuccessful();
});

it('can render game schedule edit form', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    $this->get(GameScheduleResource::getUrl('edit', [
        'record' => GameScheduleFactory::new()
            ->for($federation)
            ->for($league)
            ->create(),
    ]))->assertSuccessful();
});
