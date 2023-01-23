# Installierung

Dieses Paket ist für [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation) zugeschnitten.

Stellen Sie sicher, dass Sie das Admin-Panel installiert haben, bevor Sie mit der Installation fortfahren. Sie können die [Dokumentation hier](https://filamentphp.com/docs/2.x/admin/installation) einsehen.

Ein Event- und Turniermanagement-Plugin für das [Filament Admin Panel v2.x](https://filamentphp.com/docs/2.x/admin/installation), das in der ersten Implementierung für Dartvereine gedacht ist. Hier können Sie Vereine, Mannschaften, Spiele, Spieler und Spieltage einschließlich Punktetabellen verwalten.


## Sie können das Paket über Composer installieren:

```bash
composer require maggomann/filament-tournament-league-administration
```

## Sie können die Migrationen veröffentlichen und ausführen mit:

```bash
php artisan filament-tournament-league-administration:install-with-addressable
php artisan migrate
```

oder

```bash
php artisan vendor:publish --tag="filament-tournament-league-administration-migrations"
php artisan migrate
```

## Optional können Sie den Seeder auch mit ausführen:

```bash
php artisan db:seed --class=FilamentTournamentTableSeeder
```

## Dies ist der Inhalt der veröffentlichten Konfigurationsdatei:

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
