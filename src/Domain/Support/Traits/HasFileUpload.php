<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

trait HasFileUpload
{
    public static function getFileUploadInput(string $field = 'Upload'): FileUpload|SpatieMediaLibraryFileUpload
    {
        $fileUpload = config('filament-tournament-league-administration.form_file_upload');
        $maxSize = config('filament-tournament-league-administration.file_upload.max_size');

        return $fileUpload::make('default')
            ->label($field)
            ->preserveFilenames()
            ->image()
            ->maxSize($maxSize) // 5 MB TODO: config
            ->enableOpen()
            ->enableDownload()
            ->imagePreviewHeight('250')
            ->loadingIndicatorPosition('left')
            ->panelAspectRatio('2:1')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }

    public static function getFileUploadColumn(string $field = 'Upload'): ImageColumn|SpatieMediaLibraryImageColumn
    {
        $imageColumn = config('filament-tournament-league-administration.table_image_column');

        return $imageColumn::make('default')
            ->label($field)
            ->height(50)
            ->width(50);
    }
}
