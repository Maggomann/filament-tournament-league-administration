[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-phpstan.yml?branch%3Abeta&label=code%20style)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-phpstan+branch%3Abeta) [![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-tests.yml?branch%3Abeta&label=tests)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-tests+branch%3Abeta) [![GitHub license](https://img.shields.io/github/license/Maggomann/filament-tournament-league-administration)](https://github.com/Maggomann/filament-tournament-league-administration/blob/beta/LICENSE.md) [![Total Downloads](https://img.shields.io/packagist/dt/maggomann/filament-tournament-league-administration.svg?style=flat-square)](https://packagist.org/packages/maggomann/filament-tournament-league-administration)

---

## filament-tournament-league-administration plugin for the Filament admin panel

This package is tailored for [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation).

Make sure you have installed the admin panel before you continue with the installation. You can check the [documentation here](https://filamentphp.com/docs/2.x/admin/installation)

An event and tournament management plugin for the [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation), intended for dart clubs in the first implementation. Here you can manage clubs, teams, matches, players and match days including score tables. More detailed information can be found later in the [documentation](https://maggomann.github.io/filament-tournament-league-administration/).

## Installation

You can install the package via composer:

```bash
composer require maggomann/filament-tournament-league-administration
```

You can publish and run the migrations with:

```bash
php artisan filament-tournament-league-administration:install-with-addressable
php artisan migrate
```

or

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-migrations"
php artisan migrate
```

Optionally, you can run the seeder with:

```bash
php artisan db:seed --class=FilamentTournamentTableSeeder
```

This is the contents of the published config file:

```php
<?php

return [
    /**
     * Supported content editors: richtext & markdown:
     *      \Filament\Forms\Components\RichEditor::class
     *      \Filament\Forms\Components\MarkdownEditor::class
     */
    'editor' => \Filament\Forms\Components\RichEditor::class,

    /**
     * Buttons for text editor toolbar.
     */
    'toolbar_buttons' => [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'h2',
        'h3',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'undo',
    ],

    /**
     *  Resources
     */
    'resources' => [],

    /**
     * Supported file upload classes:
     *      \Filament\Forms\Components\FileUpload::class
     *
     *      it supports this only in combination with:
     *          table_image_column => \Filament\Tables\Columns\ImageColumn
     * -----------------------------------------------------------------------------------------
     *      \Filament\Forms\Components\SpatieMediaLibraryFileUpload::class
     *
     *      it supports this only in combination with:
     *          table_image_column => \Filament\Tables\Columns\SpatieMediaLibraryImageColumn::class
     */
    'form_file_upload' => env('MM_FORM_FILE_UPLOAD', \Filament\Forms\Components\FileUpload::class),

    /**
     * Supported image column classes:
     *      \Filament\Tables\Columns\ImageColumn
     *
     *      it supports this only in combination with:
     *          form_file_upload => \Filament\Forms\Components\FileUpload::class
     * -----------------------------------------------------------------------------------------
     *      \Filament\Tables\Columns\SpatieMediaLibraryImageColumn::class
     *
     *      it supports this only in combination with:
     *          form_file_upload => \Filament\Forms\Components\SpatieMediaLibraryFileUpload::class
     */
    'table_image_column' => env('MM_TABLE_IMAGE_COLUMN', \Filament\Tables\Columns\ImageColumn::class),

    'file_upload' => [
        'max_size' => 1024 * 2, // 2 MB
    ],
];
```

## File uploads

If you want to use the [filament/spatie-laravel-media-library-plugin](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation#requirements) package that is already installed in the background, you need to publish the data and run the migration.

You must publish the migration to create the media table.

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
```

Run the migrations:

```bash
php artisan migrate
```

or publish the migration and migrate the table with

```bash
php artisan filament-tournament-league-administration:publish-media-plugin-and-migrate
```

## Testing

```bash
composer test
```

or with coverage

```bash
composer test:pest-coverage
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Marco Ehrt](https://github.com/Maggomann)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

![verbaende](./src/docs/assets/001_verbaende.png)

![verbaend bearbeiten](./src/docs/assets/002_verband_bearbeiten.png)

![verbaend bearbeiten](./src/docs/assets/004_verband_loeschen.png)

![spielplan bearbeiten](./src/docs/assets/006_spielplan_bearbeiten.png)

![spielplan teams](./src/docs/assets/008_spielplan_teams.png)

![spielplan punktetabelle](./src/docs/assets/011_spielplan_punktetabelle.png)

![spiel erstellen reiter punkte](./src/docs/assets/010_spiel_erstellen_reiter_punkte.png)
