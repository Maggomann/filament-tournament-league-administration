
<?php

use Database\Factories\FederationFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource\RelationManagers\LeaguesRelationManager;
use function Pest\Livewire\livewire;

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

it('can render all federation relation managers', function () {
    $federation = FederationFactory::new()->create([]);

    livewire(LeaguesRelationManager::class, [
        'ownerRecord' => $federation,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($federation->leagues);
});
