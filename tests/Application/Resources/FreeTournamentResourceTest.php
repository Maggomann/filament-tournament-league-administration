
<?php

use Database\Factories\FreeTournamentFactory;
use Illuminate\Support\Fluent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\DartType;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Mode;
use Maggomann\FilamentTournamentLeagueAdministration\Models\QualificationLevel;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\EventLocalctionAddressRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource;
use function Pest\Livewire\livewire;

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

it('can create a free tournament', function () {
    $fluent = new Fluent([
        'mode_id' => Mode::inRandomOrder()->first()->id,
        'dart_type_id' => DartType::inRandomOrder()->first()->id,
        'qualification_level_id' => QualificationLevel::inRandomOrder()->first()->id,
        'maximum_number_of_participants' => random_int(1, 10),
        'coin_money' => random_int(20, 250),
        'prize_money_depending_on_placement' => [
            '1. Platz' => 'Test',
        ],
        'started_at' => now()->toDateTimeString(),
        'ended_at' => now()->addHour()->toDateTimeString(),
    ]);

    livewire(FreeTournamentResource\Pages\CreateFreeTournament::class)
        ->fillForm([
            'name' => 'Example',
            'slug' => 'example',
            'description' => 'Description',
            'mode_id' => $fluent->mode_id,
            'dart_type_id' => $fluent->dart_type_id,
            'qualification_level_id' => $fluent->qualification_level_id,
            'maximum_number_of_participants' => $fluent->maximum_number_of_participants,
            'coin_money' => $fluent->coin_money,
            'prize_money_depending_on_placement' => $fluent->prize_money_depending_on_placement,
            'started_at' => $fluent->started_at,
            'ended_at' => $fluent->ended_at,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    tap(
        FreeTournament::where([
            'name' => 'Example',
            'slug' => 'example',
            'description' => 'Description',
            'mode_id' => $fluent->mode_id,
            'dart_type_id' => $fluent->dart_type_id,
            'qualification_level_id' => $fluent->qualification_level_id,
            'maximum_number_of_participants' => $fluent->maximum_number_of_participants,
            'coin_money' => $fluent->coin_money,
        ])->first(),
        function (FreeTournament $freeTournament) use ($fluent) {
            $this->assertDatabaseHas(FreeTournament::class, [
                'id' => $freeTournament->id,
                'name' => 'Example',
                'slug' => 'example',
                'description' => 'Description',
                'mode_id' => $fluent->mode_id,
                'dart_type_id' => $fluent->dart_type_id,
                'qualification_level_id' => $fluent->qualification_level_id,
                'maximum_number_of_participants' => $fluent->maximum_number_of_participants,
                'coin_money' => $fluent->coin_money,
            ]);
        }
    );
});

it('can save a free tournament', function () {
    $freeTournament = FreeTournamentFactory::new()->create();

    $fluent = new Fluent([
        'mode_id' => Mode::inRandomOrder()->first()->id,
        'dart_type_id' => DartType::inRandomOrder()->first()->id,
        'qualification_level_id' => QualificationLevel::inRandomOrder()->first()->id,
        'maximum_number_of_participants' => random_int(1, 10),
        'coin_money' => random_int(20, 250),
        'prize_money_depending_on_placement' => [
            '1. Platz' => 'Test',
        ],
        'started_at' => now()->toDateTimeString(),
        'ended_at' => now()->addHour()->toDateTimeString(),
    ]);

    livewire(FreeTournamentResource\Pages\EditFreeTournament::class, [
        'record' => $freeTournament->getRouteKey(),
    ])
        ->fillForm([
            'name' => 'Edit Example',
            'slug' => 'edit-example',
            'description' => 'Edit Description',
            'mode_id' => $fluent->mode_id,
            'dart_type_id' => $fluent->dart_type_id,
            'qualification_level_id' => $fluent->qualification_level_id,
            'maximum_number_of_participants' => $fluent->maximum_number_of_participants,
            'coin_money' => $fluent->coin_money,
            'prize_money_depending_on_placement' => $fluent->prize_money_depending_on_placement,
            'started_at' => $fluent->started_at,
            'ended_at' => $fluent->ended_at,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    tap(
        $freeTournament->refresh(),
        function (FreeTournament $freeTournament) use ($fluent) {
            $this->assertDatabaseHas(FreeTournament::class, [
                'id' => $freeTournament->id,
                'name' => 'Edit Example',
                'slug' => 'edit-example',
                'description' => 'Edit Description',
                'mode_id' => $fluent->mode_id,
                'dart_type_id' => $fluent->dart_type_id,
                'qualification_level_id' => $fluent->qualification_level_id,
                'maximum_number_of_participants' => $fluent->maximum_number_of_participants,
                'coin_money' => $fluent->coin_money,
            ]);
        }
    );
});
