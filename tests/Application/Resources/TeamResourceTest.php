
<?php

use Database\Factories\FederationFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\LeagueFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\Actions\UpdateOrCreateTeamAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Team\DTO\TeamData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\RelationManagers\PlayersRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;
use function Pest\Livewire\livewire;

uses(TestCase::class);

it('can render team list table', function () {
    $this->get(TeamResource::getUrl('index'))->assertSuccessful();
});

it('can render team create form', function () {
    $this->get(TeamResource::getUrl('create'))->assertSuccessful();
});

it('can render team edit form', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();
    $team = TeamFactory::new()->for($league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $this->get(TeamResource::getUrl('edit', [
        'record' => $team,
    ]))->assertSuccessful();
});

it('can render all player relation managers', function () {
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();
    $team = TeamFactory::new()->for($league)->create();
    $team->gameSchedules()->save($gameSchedule);

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
    $federation = FederationFactory::new()->create();
    $league = LeagueFactory::new()->for($federation)->create();

    livewire(TeamResource\Pages\CreateTeam::class)
        ->fillForm([
            'federation_id' => $federation->id,
            'league_id' => $league->id,
            'name' => null,
            'slug' => 'example',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
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

    $gameSchedule = GameScheduleFactory::new()
        ->for($federation)
        ->for($league)
        ->create();
    $team = TeamFactory::new()->for($league)->create();
    $team->gameSchedules()->save($gameSchedule);

    $team = TeamFactory::new()->create();

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
