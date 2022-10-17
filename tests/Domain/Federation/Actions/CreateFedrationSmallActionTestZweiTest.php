<?php

use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\Actions\CreateFedrationSmallAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Federation\DTO\FederationData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;

test('test zwei', function () {
    $federationData = FederationData::create([
        'calculation_type_id' => 2,
        'name' => 'Example 2',
    ]);

    // $federation = new Federation();
    // $federation->fill($federationData->toArray());
    // $federation->calculation_type_id = $federationData->calculation_type_id;

    // $federation->saveQuietly();

    $federation = app(CreateFedrationSmallAction::class)->execute($federationData);

    expect(true)->toBeTrue();
});
