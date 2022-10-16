<?php

use Maggomann\FilamentTournamentLeagueAdministration\Tests\TestCase;

uses(TestCase::class);

test('asserts true is true', function () {
    $this->assertTrue(true);

    expect(true)->toBeTrue();
});
