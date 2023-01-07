[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-phpstan.yml?branch%3Abeta&label=code%20style)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-phpstan+branch%3Abeta) [![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Maggomann/filament-tournament-league-administration/run-tests.yml?branch%3Abeta&label=tests)](https://github.com/Maggomann/filament-tournament-league-administration/actions?query=workflow%3Arun-tests+branch%3Abeta) [![GitHub license](https://img.shields.io/github/license/Maggomann/filament-tournament-league-administration)](https://github.com/Maggomann/filament-tournament-league-administration/blob/beta/LICENSE.md)

---

# Work in progress (wip)

This package is still under development. Use at your own risk.

---

I am programming the project on the side in my spare time.

Before the package leaves beta status, I would like to implement the following:

- <s>Image upload for the areas</s>
  - <s>Players</s>
  - <s>Federations</s>
  - <s>League</s>
  - <s>Free Tournament</s>
- Authorization protection
- <s>Calculate, save and display team standings</s>
- Minimum test coverage of 90%
- Extension of user data
  - Nickname
  - etc.
- Bring codebase to phpstan level 6

---

**Later extensions:**

- Update to filament 3.X
- Improved usability:
  - Creation and editing of records via integrated modal forms

---

![verbaende](./src/docs/assets/001_verbaende.png)

![verbaend bearbeiten](./src/docs/assets/002_verband_bearbeiten.png)

![verbaend bearbeiten](./src/docs/assets/004_verband_loeschen.png)

![spielplan bearbeiten](./src/docs/assets/006_spielplan_bearbeiten.png)

![spielplan teams](./src/docs/assets/008_spielplan_teams.png)

![spielplan punktetabelle](./src/docs/assets/011_spielplan_punktetabelle.png)

![spiel erstellen reiter punkte](./src/docs/assets/010_spiel_erstellen_reiter_punkte.png)


## filament-tournament-league-administration plugin for the Filament admin panel

An event and tournament management plugin for the [Filament admin panel](https://filamentphp.com/) in version 2.x, intended for dart clubs in the first implementation. Here you can manage clubs, teams, matches, players and match days including score tables. More detailed information can be found later in the documentation yet to be implemented.

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

```
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
````
## File uploads

If you want to use the [filament/spatie-laravel-media-library-plugin](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation#requirements) package that is already installed in the background, you need to publish the data and run ide migration.

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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
