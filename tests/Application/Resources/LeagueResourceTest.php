<?php

use Database\Factories\FederationFactory;
use Database\Factories\LeagueFactory;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
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

it('can create a league', function () {
    $federation = FederationFactory::new()->create();

    livewire(LeagueResource\Pages\CreateLeague::class)
        ->fillForm([
            'federation_id' => $federation->id,
            'name' => 'Example',
            'slug' => 'example',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(League::class, [
        'federation_id' => $federation->id,
        'name' => 'Example',
        'slug' => 'example',
    ]);
});

it('can validate input for player page', function () {
    livewire(LeagueResource\Pages\CreateLeague::class)
        ->fillForm([
            'federation_id' => null,
            'name' => null,
            'slug' => 'example',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'federation_id' => 'required',
            'name' => 'required',
        ]);
});

it('can save a league', function () {
    $federation = FederationFactory::new()->create();

    $league = LeagueFactory::new()->create();

    livewire(LeagueResource\Pages\EditLeague::class, [
        'record' => $league->getRouteKey(),
    ])
    ->fillForm([
        'federation_id' => $federation->id,
        'name' => 'Example Edit',
        'slug' => 'example-edit',
    ])
    ->call('save')
    ->assertHasNoFormErrors();

    expect($league->refresh())
        ->name->toBe('Example Edit')
        ->slug->toBe('example-edit')
        ->federation_id->toBe($federation->id);

    $this->assertDatabaseHas(League::class, [
        'id' => $league->id,
        'federation_id' => $federation->id,
        'name' => 'Example Edit',
        'slug' => 'example-edit',
    ]);
});

it('can delete a league', function () {
    $league = LeagueFactory::new()->create();

    livewire(LeagueResource\Pages\EditLeague::class, [
        'record' => $league->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($league);
});
