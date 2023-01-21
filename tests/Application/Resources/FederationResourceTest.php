
<?php

use Database\Factories\FederationFactory;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
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

it('can create a federtion', function () {
    $calculationType = CalculationType::first();

    livewire(FederationResource\Pages\CreateFederation::class)
        ->fillForm([
            'name' => 'Example',
            'slug' => 'example',
            'calculation_type_id' => $calculationType->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    tap(
        Federation::where([
            'name' => 'Example',
            'slug' => 'example',
            'calculation_type_id' => $calculationType->id,
        ])->first(),
        function (Federation $federation) use ($calculationType) {
            $this->assertDatabaseHas(Federation::class, [
                'id' => $federation->id,
                'name' => 'Example',
                'calculation_type_id' => $calculationType->id,
            ]);
        }
    );
});

it('can save a federation', function () {
    $calculationType = CalculationType::first();
    $federation = FederationFactory::new()->create([]);

    livewire(FederationResource\Pages\EditFederation::class, [
        'record' => $federation->getRouteKey(),
    ])
        ->fillForm([
            'name' => 'Edit Example',
            'calculation_type_id' => $calculationType->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    tap(
        $federation->refresh(),
        function (Federation $federation) use ($calculationType) {
            $this->assertDatabaseHas(Federation::class, [
                'id' => $federation->id,
                'name' => 'Edit Example',
                'slug' => 'edit-example',
                'calculation_type_id' => $calculationType->id,
            ]);
        }
    );
});

it('can validate input for federation page', function () {
    livewire(FederationResource\Pages\CreateFederation::class)
        ->fillForm([
            'name' => null,
            'calculation_type_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'calculation_type_id' => 'required',
        ]);
});

it('can delete a federation', function () {
    $federation = FederationFactory::new()->create([]);

    livewire(FederationResource\Pages\EditFederation::class, [
        'record' => $federation->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);

    $this->assertSoftDeleted($federation);
});
