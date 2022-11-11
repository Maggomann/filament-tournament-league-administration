<?php

use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\Actions\CreateFedrationSmallAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\DTO\FederationData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

it('adds a federation', function ($calculationTypeId, $name) {
    $federationData = FederationData::from([
        'calculation_type_id' => $calculationTypeId,
        'name' => $name,
    ]);

    $federation = app(CreateFedrationSmallAction::class)->execute($federationData);

    $this->assertDatabaseHas(Federation::class, [
        'id' => $federation->id,
        'calculation_type_id' => $calculationTypeId,
        'name' => $name,
    ]);
})->with([
    [1, 'Example 1'],
    [2, 'Example 2'],
]);
