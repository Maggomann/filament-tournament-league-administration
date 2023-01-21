
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions\UpdateOrCreateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\RelationManagers\PlayersRelationManager;
use function Pest\Livewire\livewire;

it('can render team list table', function () {
    $this->get(TeamResource::getUrl('index'))->assertSuccessful();
});

it('can render team create form', function () {
    $this->get(TeamResource::getUrl('create'))->assertSuccessful();
});

it('can render team edit form', function () {
    $team = TeamFactory::new()
        ->withPlausibleRelations()
        ->create();

    $this->get(TeamResource::getUrl('edit', [
        'record' => $team,
    ]))->assertSuccessful();
});

it('can render all player relation managers', function () {
    $team = TeamFactory::new()
        ->withPlausibleRelations()
        ->create();

    livewire(PlayersRelationManager::class, [
        'ownerRecord' => $team,
    ])
        ->assertSuccessful();
});

it('can create a team', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    livewire(TeamResource\Pages\CreateTeam::class)
        ->fillForm([
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'name' => 'Example',
            'slug' => 'example',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Team::class, [
        'league_id' => $league->id,
        'name' => 'Example',
        'slug' => 'example',
    ]);
});

it('can validate input for team page', function () {
    livewire(TeamResource\Pages\CreateTeam::class)
        ->fillForm([
            'federation_id' => null,
            'league_id' => null,
            'name' => null,
            'slug' => 'example',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'federation_id' => 'required',
            'league_id' => 'required',
            'name' => 'required',
        ]);
});

it('team create page should receive execute from UpdateOrCreateTeamAction', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    $mock = $this->mock(UpdateOrCreateTeamAction::class);
    $mock->shouldReceive('execute')
        ->with(TeamData::class)
        ->once()
        ->andReturn(Team::class);

    $createTeam = new TeamResource\Pages\CreateTeam();
    $createTeam->form->fill([
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'name' => 'Example',
        'slug' => 'example',
    ]);
    $createTeam->create();
});

it('can save a team', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();

    $team = TeamFactory::new()
        ->withPlausibleRelations()
        ->create();

    livewire(TeamResource\Pages\EditTeam::class, [
        'record' => $team->getRouteKey(),
    ])
    ->fillForm([
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'name' => 'Example Edit',
        'slug' => 'example-edit',
    ])
    ->call('save')
    ->assertHasNoFormErrors();

    expect($team->refresh())
        ->name->toBe('Example Edit')
        ->slug->toBe('example-edit');

    $this->assertDatabaseHas(Team::class, [
        'id' => $team->id,
        'league_id' => $league->id,
        'name' => 'Example Edit',
        'slug' => 'example-edit',
    ]);
});

it('team edit page should receive execute from UpdateOrCreateTeamAction', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();

    $team = TeamFactory::new()
        ->withPlausibleRelations()
        ->create();

    $mock = $this->mock(UpdateOrCreateTeamAction::class);
    $mock->shouldReceive('execute')
        ->with(TeamData::class, Team::class)
        ->once()
        ->andReturn(Team::class);

    $editTeam = new TeamResource\Pages\EditTeam();
    $editTeam->record = $team;
    $editTeam->form->fill([
        'federation_id' => $federation->id,
        'league_id' => $league->id,
        'name' => 'Example Test',
        'slug' => 'example-test',
    ]);
    $editTeam->save();
});

it('can delete a team', function () {
    $team = TeamFactory::new()
        ->withPlausibleRelations()
        ->create();

    livewire(TeamResource\Pages\EditTeam::class, [
        'record' => $team->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($team);
});
