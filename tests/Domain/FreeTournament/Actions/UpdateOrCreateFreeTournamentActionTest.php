<?php

use Database\Factories\FreeTournamentFactory;
use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\Actions\UpdateOrCreateFreeTournamentAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\FreeTournament\DTO\FreeTournamentData;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

dataset('UpdateOrCreateFreeTournaments', function () {
    yield fn () => FreeTournamentData::from([
        'mode_id' => 1,
        'dart_type_id' => 1,
        'qualification_level_id' => 1,
        'name' => 'name example',
        'description' => 'description example',
        'maximum_number_of_participants' => 1,
        'coin_money' => 5,
        'prize_money_depending_on_placement' => ['1st place' => '1st prize'],
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ]);

    yield fn () => FreeTournamentData::from([
        'mode_id' => 1,
        'dart_type_id' => 1,
        'qualification_level_id' => 1,
        'name' => 'name example',
        'description' => 'description example',
        'maximum_number_of_participants' => 1,
        'coin_money' => 5,
        'prize_money_depending_on_placement' => ['1st place' => '1st prize'],
        'started_at' => now()->toString(),
        'ended_at' => now()->toString(),
    ]);
});

it('creates an free tournament', function (FreeTournamentData $freeTournamentData) {
    $freeTournament = app(UpdateOrCreateFreeTournamentAction::class)->execute($freeTournamentData);

    $assertAttributes = $freeTournament->attributesToArray();

    Arr::set(
        $assertAttributes,
        'prize_money_depending_on_placement',
        $this->castAsJson(
            Arr::get($assertAttributes, 'prize_money_depending_on_placement')
        )
    );

    $this->assertDatabaseHas(
        FreeTournament::class,
        $assertAttributes
    );
})->with('UpdateOrCreateFreeTournaments');

it('updates an free tournament', function (FreeTournamentData $freeTournamentData) {
    $freeTournament = app(UpdateOrCreateFreeTournamentAction::class)->execute(
        $freeTournamentData,
        FreeTournamentFactory::new()->create()
    );

    $assertAttributes = $freeTournament->attributesToArray();

    Arr::set(
        $assertAttributes,
        'prize_money_depending_on_placement',
        $this->castAsJson(
            Arr::get($assertAttributes, 'prize_money_depending_on_placement')
        )
    );

    $this->assertDatabaseHas(
        FreeTournament::class,
        $assertAttributes
    );
})->with('UpdateOrCreateFreeTournaments');
