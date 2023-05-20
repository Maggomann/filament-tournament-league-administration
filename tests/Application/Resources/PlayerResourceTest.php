<?php

use Database\Factories\FederationFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\Actions\UpdateOrCreatePlayerAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Player\DTO\PlayerData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\AddressesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\RelationManagers\TeamRelationManager;
use function Pest\Livewire\livewire;

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

it('can create a player', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $team = TeamFactory::new()->for($league)->create();

    livewire(PlayerResource\Pages\CreatePlayer::class)
        ->fillForm([
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'team_id' => $team->id,
            'name' => 'Example',
            'slug' => 'example',
            'email' => 'example@example.com',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Player::class, [
        'team_id' => $team->id,
        'name' => 'Example',
        'slug' => 'example',
        'email' => 'example@example.com',
    ]);
});

it('can validate input for player page', function () {
    livewire(PlayerResource\Pages\CreatePlayer::class)
        ->fillForm([
            'federation_id' => null,
            'league_id' => null,
            'team_id' => null,
            'name' => null,
            'slug' => 'example',
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'federation_id' => 'required',
            'league_id' => 'required',
            'team_id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);
});

it('player create page should receive execute from UpdateOrCreatePlayerAction', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $team = TeamFactory::new()->for($league)->create();

    $mock = $this->mock(UpdateOrCreatePlayerAction::class);
    $mock->shouldReceive('execute')
        ->with(PlayerData::class)
        ->once()
        ->andReturn(Player::class);

    $createPlayer = new PlayerResource\Pages\CreatePlayer();
    $createPlayer->form->fill([
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'team_id' => $team->id,
        'name' => 'Example',
        'slug' => 'example',
        'email' => 'example@example.com',
    ]);
    $createPlayer->create();
});

it('can save a player', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $team = TeamFactory::new()->for($league)->create();

    $player = PlayerFactory::new()
        ->withPlausibleBelongsToRelations()
        ->create();

    livewire(PlayerResource\Pages\EditPlayer::class, [
        'record' => $player->getRouteKey(),
    ])
        ->fillForm([
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'team_id' => $team->id,
            'name' => 'Example Edit',
            'slug' => 'example-edit',
            'email' => 'example-edit@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($player->refresh())
        ->name->toBe('Example Edit')
        ->slug->toBe('example-edit')
        ->email->toBe('example-edit@example.com');

    $this->assertDatabaseHas(Player::class, [
        'id' => $player->id,
        'team_id' => $team->id,
        'name' => 'Example Edit',
        'slug' => 'example-edit',
        'email' => 'example-edit@example.com',
    ]);
});

it('player edit page should receive execute from UpdateOrCreateTeamAction', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();
    $team = TeamFactory::new()->for($league)->create();

    $player = PlayerFactory::new()
        ->withPlausibleBelongsToRelations()
        ->create();

    $mock = $this->mock(UpdateOrCreatePlayerAction::class);
    $mock->shouldReceive('execute')
        ->with(PlayerData::class, Player::class)
        ->once()
        ->andReturn(Player::class);

    $editPlayer = new PlayerResource\Pages\EditPlayer();
    $editPlayer->record = $player;
    $editPlayer->form->fill([
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'team_id' => $team->id,
        'name' => 'Example',
        'slug' => 'example',
        'email' => 'example@example.com',
    ]);
    $editPlayer->save();
});

it('can delete a team', function () {
    $player = PlayerFactory::new()
        ->withPlausibleBelongsToRelations()
        ->create();

    livewire(PlayerResource\Pages\EditPlayer::class, [
        'record' => $player->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($player);
});
