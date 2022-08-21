<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Resources\Resource;
use Maggomann\FilamentModelTranslator\Contracts\TranslateableModels;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModels;

class TranslateableResource extends Resource implements TranslateableModels
{
    use HasTranslateableModels;

    protected static ?string $translateableKey = 'filament-tournament-league-administration::';
}
