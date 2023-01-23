# Installation

This package is tailored for [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation).

Make sure you have installed the admin panel before you continue with the installation. You can check the [documentation here](https://filamentphp.com/docs/2.x/admin/installation)

An event and tournament management plugin for the [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation), intended for dart clubs in the first implementation. Here you can manage clubs, teams, matches, players and match days including score tables.

## You can install the package via composer:

```bash
composer require maggomann/filament-tournament-league-administration
```

## You can publish and run the migrations with:

```bash
php artisan filament-tournament-league-administration:install-with-addressable
php artisan migrate
```

or

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-migrations"
php artisan migrate
```

## Optionally, you can run the seeder with:

```bash
php artisan db:seed --class=FilamentTournamentTableSeeder
```

## This is the contents of the published config file:

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
    // 'form_file_upload' => env('MM_FORM_FILE_UPLOAD', \Filament\Forms\Components\SpatieMediaLibraryFileUpload::class),

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
    // 'table_image_column' => env('MM_TABLE_IMAGE_COLUMN', \Filament\Tables\Columns\SpatieMediaLibraryImageColumn::class),

    'file_upload' => [
        'max_size' => 1024 * 2, // 2 MB
    ],
];
```
