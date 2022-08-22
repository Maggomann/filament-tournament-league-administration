<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Resources\Resource;
use Maggomann\FilamentModelTranslator\Contracts\TranslateableResources;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableResources;

class TranslateableResource extends Resource implements TranslateableResources
{
    use HasTranslateableResources;

    protected static ?string $translateablePackageKey = 'filament-tournament-league-administration::';
}
