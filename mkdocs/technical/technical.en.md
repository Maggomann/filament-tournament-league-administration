# Technical

## .env

```console
# for filament classes
MM_TABLE_IMAGE_COLUMN=\Filament\Tables\Columns\ImageColumn::class
MM_FORM_FILE_UPLOAD=\Filament\Forms\Components\FileUpload::class

# for spatie media library
MM_TABLE_IMAGE_COLUMN=\Filament\Tables\Columns\SpatieMediaLibraryImageColumn::class
MM_FORM_FILE_UPLOAD=\Filament\Forms\Components\SpatieMediaLibraryFileUpload::class
```

## file upload

You can use the two env variables to specify the classes you want to work with for file upload. By default, the package uses the filament classes for file upload and for displaying uploaded images. However, if you want to use the [filament/spatie-laravel-media-library-plugin](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation#requirements), you can do so and modify the env variables accordingly.

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
