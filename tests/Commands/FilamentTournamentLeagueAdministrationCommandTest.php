
<?php

it('can install all of the filament-tournament-league-administration resources', function () {
    $this->artisan('filament-tournament-league-administration:install')
        ->expectsOutput('Publishing tournament-league-administration Configuration...')
        ->expectsOutput('Publishing Filament tournament-league-administration Migrations...')
        ->expectsOutput('Publishing Filament tournament-league-administration languages...')
        ->expectsOutput('Filament filament-tournament-league-administration was installed successfully.')
        ->assertExitCode(0);
});
