
<?php

use Database\Factories\LeagueFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\RelationManagers\PlayersRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\RelationManagers\TeamsRelationManager;
use function Pest\Livewire\livewire;

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

it('can render all league relation managers', function () {
    $league = LeagueFactory::new()->create();

    livewire(TeamsRelationManager::class, [
        'ownerRecord' => $league,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($league->teams);

    livewire(PlayersRelationManager::class, [
        'ownerRecord' => $league,
    ])
        ->assertSuccessful()
        ->assertCanNotSeeTableRecords($league->players);
});
