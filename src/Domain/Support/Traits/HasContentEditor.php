<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits;

use Filament\Forms\Components\RichEditor;

trait HasContentEditor
{
    public static function getContentEditor(string $field): RichEditor
    {
        $defaultEditor = config('filament-tournament-league-administration.editor');

        return $defaultEditor::make($field)
            ->required()
            ->toolbarButtons(config('filament-tournament-league-administration.toolbar_buttons'))
            ->columnSpan([
                'sm' => 2,
            ]);
    }
}
