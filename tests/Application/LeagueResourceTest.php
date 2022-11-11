
<?php

use Database\Factories\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('can render league list table', function () {
    $this->get(LeagueResource::getUrl('index'))->assertSuccessful();
});

it('can render league create form', function () {
    $this->get(LeagueResource::getUrl('create'))->assertSuccessful();
});

it('can render league edit form', function () {
    $this->get(LeagueResource::getUrl('edit', [
        'record' => LeagueFactory::new()->create(),
    ]))->assertSuccessful();
});
