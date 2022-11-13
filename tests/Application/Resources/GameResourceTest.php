
<?php

use Database\Factories\GameDayFactory;
use Database\Factories\GameFactory;
use Database\Factories\GameScheduleFactory;
use Database\Factories\TeamFactory;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource;

it('can render game list table', function () {
    $this->get(GameResource::getUrl('index'))->assertSuccessful();
});

it('can render game create form', function () {
    $this->get(GameResource::getUrl('create'))->assertSuccessful();
});

it('can render game edit form', function () {
    $gameSchedule = GameScheduleFactory::new()->create();
    $homeTeam = TeamFactory::new()->create();
    $homeTeam->gameSchedules()->save($gameSchedule);
    $guestTeam = TeamFactory::new()->create();
    $guestTeam->gameSchedules()->save($gameSchedule);

    $this->get(GameResource::getUrl('edit', [
        'record' => GameFactory::new()
            ->for($gameSchedule)
            ->for(GameDayFactory::new()->for($gameSchedule))
            ->for($homeTeam, 'homeTeam')
            ->for($guestTeam, 'guestTeam')
            ->create(),
    ]))->assertSuccessful();
});
