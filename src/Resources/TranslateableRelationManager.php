<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Maggomann\FilamentModelTranslator\Contracts\Translateable;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableRelationManager;

class TranslateableRelationManager extends RelationManager implements Translateable
{
    use HasTranslateableRelationManager;

    protected static ?string $translateablePackageKey = 'filament-tournament-league-administration::';
}
