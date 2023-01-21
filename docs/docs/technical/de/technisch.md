# Technisch

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

Mit den beiden env-Variablen können Sie die Klassen bestimmen, mit denen Sie für den Datei-Upload arbeiten wollen. Standardmäßig verwendet das Paket die filament-Klassen für den Datei-Upload und für die Anzeige der hochgeladenen Bilder. Wenn Sie aber das [filament/spatie-laravel-media-library-plugin](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation#requirements) verwenden wollen, können Sie das tun und die env-Variablen entsrpechend anpassen.

Wenn Sie das Paket [filament/spatie-laravel-media-library-plugin](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation#requirements) verwenden möchten, das bereits im Hintergrund installiert ist, müssen Sie die Daten veröffentlichen und die Migration ausführen.

Sie müssen die Migration veröffentlichen, um die Medientabelle zu erstellen.

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
```

Führen Sie die Migrationen durch:
  
```bash
php artisan migrate
```
  
oder veröffentlichen Sie die Migration und migrieren Sie die Tabelle mit

```bash
php artisan filament-tournament-league-administration:publish-media-plugin-and-migrate
```