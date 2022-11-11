
<?php

use Database\Factories\FederationFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('can render federation list table', function () {
    $this->get(FederationResource::getUrl('index'))->assertSuccessful();
});

it('can render federation create form', function () {
    $this->get(FederationResource::getUrl('create'))->assertSuccessful();
});

it('can render federation edit form', function () {
    $this->get(FederationResource::getUrl('edit', [
        'record' => FederationFactory::new()->create(),
    ]))->assertSuccessful();
});
