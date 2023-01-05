<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

trait HasFileUpload
{
    public static function getFileUploadInput(string $field = 'Upload'): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make('default')
            ->label($field)
            ->preserveFilenames()
            ->image()
            ->maxSize(1024 * 5) // 5 MB TODO: config
            ->enableOpen()
            ->enableDownload()
            ->imagePreviewHeight('250')
            ->loadingIndicatorPosition('left')
            ->panelAspectRatio('2:1')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }

    public static function getFileUploadColumn(string $field = 'Upload'): SpatieMediaLibraryImageColumn
    {
        return SpatieMediaLibraryImageColumn::make('default')
            ->label($field)
            ->height(50)
            ->width(50);
    }
}
