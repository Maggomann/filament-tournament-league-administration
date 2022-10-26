<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits;

trait HasContentEditor
{
    public static function getContentEditor(string $field)
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
