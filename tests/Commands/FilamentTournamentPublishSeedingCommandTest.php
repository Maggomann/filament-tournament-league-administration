
<?php

it('can install the tournament-league-administration tables and configurations', function () {
    $this->artisan('filament-tournament-league-administration:publish-seeding')
        ->expectsOutput('Publishing Filament tournament-league-administration seeders and factories...')
        ->expectsOutput('Filament filament-tournament-league-administration seeding files was published successfully.')
        ->assertExitCode(0);
});
