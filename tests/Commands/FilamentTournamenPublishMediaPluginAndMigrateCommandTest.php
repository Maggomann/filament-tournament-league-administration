
<?php

it('can publish filament/spatie-laravel-media-library-plugin data and migrate', function () {
    $this->artisan('filament-tournament-league-administration:publish-media-plugin-and-migrate')
        ->expectsOutput('Publishing filament/spatie-laravel-media-library-plugin data and migrate')
        ->assertExitCode(0);
});
