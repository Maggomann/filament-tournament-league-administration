
<?php

use Database\Factories\PlayerFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\AddressesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\RelationManagers\TeamRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;
use function Pest\Livewire\livewire;

uses(TestCase::class);

it('can render player list table', function () {
    $this->get(PlayerResource::getUrl('index'))->assertSuccessful();
});

it('can render player create form', function () {
    $this->get(PlayerResource::getUrl('create'))->assertSuccessful();
});

it('can render player edit form', function () {
    $player = PlayerFactory::new()
        ->withPlausibleBelongsToRelations()
        ->create();

    $this->get(PlayerResource::getUrl('edit', [
        'record' => $player,
    ]))->assertSuccessful();
});

it('can render all player relation managers', function () {
    $player = PlayerFactory::new()
        ->create();

    livewire(TeamRelationManager::class, [
        'ownerRecord' => $player,
    ])
        ->assertSuccessful();

    livewire(AddressesRelationManager::class, [
        'ownerRecord' => $player,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($player->addresses);
});
