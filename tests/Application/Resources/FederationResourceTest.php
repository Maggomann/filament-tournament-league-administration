
<?php

use Database\Factories\FederationFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource\RelationManagers\LeaguesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;
use function Pest\Livewire\livewire;

uses(TestCase::class);

it('can render federation list table', function () {
    $this->get(FederationResource::getUrl('index'))->assertSuccessful();
});

it('can render federation create form', function () {
    $this->get(FederationResource::getUrl('create'))->assertSuccessful();
});

it('can render federation edit form', function () {
    $federation = FederationFactory::new()->create();

    $this->get(FederationResource::getUrl('edit', [
        'record' => $federation,
    ]))->assertSuccessful();
});

it('can render all federation relation managers', function () {
    $federation = FederationFactory::new()->create([]);

    livewire(LeaguesRelationManager::class, [
        'ownerRecord' => $federation,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($federation->leagues);
});
