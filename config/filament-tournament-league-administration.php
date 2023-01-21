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
