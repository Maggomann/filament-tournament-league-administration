# This is my package filament-tournament-league-administration

[![GitHub Tests Action Status](https://github.com/maggomann/filament-tournament-league-administration/workflows/run-tests/badge.svg?branch=master)](https://github.com/maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-tests+branch%3Amaster)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require maggomann/filament-tournament-league-administration
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-views"
```

## Usage

```php
$filamentTournamentLeagueAdministration = new Maggomann\FilamentTournamentLeagueAdministration();
echo $filamentTournamentLeagueAdministration->echoPhrase('Hello, Maggomann!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
