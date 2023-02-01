<?php

it('can install the tournament-league-administration tables and configurations', function () {
    $this->artisan('filament-tournament-league-administration:install-with-addressable')
        ->expectsOutput('Publishing addressable Configuration...')
        ->expectsOutput('Publishing addressable languages...')
        ->expectsOutput('Publishing addressable Migrations...')
        ->expectsOutput('addressable was installed successfully.')
        ->expectsOutput('Publishing addressable languages for filament...')
        ->expectsOutput('addressable was installed successfully.')
        ->expectsOutput('Publishing tournament-league-administration Configuration...')
        ->expectsOutput('Publishing Filament tournament-league-administration Migrations...')
        ->expectsOutput('Publishing Filament tournament-league-administration languages...')
        ->expectsOutput('Publishing Filament tournament-league-administration seeders and factories...')
        ->expectsOutput('Filament filament-tournament-league-administration was installed successfully.')
        ->assertExitCode(0);
});
