<?php

use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCases\DTO\TestCase as DTOTestCase;

uses(TestCase::class)->in(
    __DIR__.'/Application',
    __DIR__.'/Commands',
    __DIR__.'/Domain/Address/Actions',
    __DIR__.'/Domain/Federation/Actions',
    __DIR__.'/Domain/FreeTournament/Actions',
    __DIR__.'/Domain/Game/Actions',
    __DIR__.'/Domain/GameDay/Actions',
    __DIR__.'/Domain/GameSchedule/Actions',
    __DIR__.'/Domain/Player/Actions',
    __DIR__.'/Domain/Team/Actions',
);

uses(DTOTestCase::class)->in(
    __DIR__.'/Domain/Address/DTO',
    __DIR__.'/Domain/Federation/DTO',
    __DIR__.'/Domain/FreeTournament/DTO',
    __DIR__.'/Domain/Game/DTO',
    __DIR__.'/Domain/GameDay/DTO',
    __DIR__.'/Domain/Player/DTO',
    __DIR__.'/Domain/Team/DTO',
);
