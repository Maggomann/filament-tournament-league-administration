
<?php

use Database\Factories\FreeTournamentFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\EventLocalctionAddressRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;
use function Pest\Livewire\livewire;

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

it('can render all free tournament relation managers', function () {
    $freeTournament = FreeTournamentFactory::new()->create();

    livewire(EventLocalctionAddressRelationManager::class, [
        'ownerRecord' => $freeTournament,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($freeTournament->addresses);
});
