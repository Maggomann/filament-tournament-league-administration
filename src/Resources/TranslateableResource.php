<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Resources\Resource;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModels;

class TranslateableResource extends Resource
{
    use HasTranslateableModels;

    protected static ?string $translateableKey = 'filament-tournament-league-administration::';

    public function transPackageKey(): ?string
    {
        return static::$translateableKey;
    }
}
