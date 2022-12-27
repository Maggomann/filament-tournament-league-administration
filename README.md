# Work in progress (wip)
This package is still under development. Use at your own risk.

---

[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-phpstan.yml?branch%3Abeta&label=code%20style)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-phpstan+branch%3Abeta) [![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-tests.yml?branch%3Abeta&label=tests)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-tests+branch%3Abeta)

---

![verbaende](./src/docs/assets/001_verbaende.png)

![verbaend bearbeiten](./src/docs/assets/002_verband_bearbeiten.png)

![verbaend bearbeiten](./src/docs/assets/004_verband_loeschen.png)

![spielplan bearbeiten](./src/docs/assets/006_spielplan_bearbeiten.png)

![spielplan teams](./src/docs/assets/008_spielplan_teams.png)

![spielplan punktetabelle](./src/docs/assets/011_spielplan_punktetabelle.png)

![spiel erstellen reiter punkte](./src/docs/assets/010_spiel_erstellen_reiter_punkte.png)


## This is my package filament-tournament-league-administration

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require maggomann/filament-tournament-league-administration
```

You can publish and run the migrations with:

```bash
php artisan filament-tournament-league-administration:install
php artisan migrate
```

or

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

Optionally, you can run the seeder with:

```bash
php artisan db:seed --class=FilamentTournamentTableSeeder
```


Optionally, you can publish the seeding file with:

```bash
php artisan filament-tournament-league-administration:publish-seeding
```
or

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-seeders"
php artisan vendor:publish --tag="filament-tournament-league-administration-factories"
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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
