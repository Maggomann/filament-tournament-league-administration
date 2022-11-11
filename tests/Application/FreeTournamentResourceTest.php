
<?php

use Database\Factories\FreeTournamentFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('can render free tournament list table', function () {
    $this->get(FreeTournamentResource::getUrl('index'))->assertSuccessful();
});

it('can render free tournament create form', function () {
    $this->get(FreeTournamentResource::getUrl('create'))->assertSuccessful();
});

it('can render free tournament edit form', function () {
    $this->get(FreeTournamentResource::getUrl('edit', [
        'record' => FreeTournamentFactory::new()->create(),
    ]))->assertSuccessful();
});
