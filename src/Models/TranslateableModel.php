<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class TranslateableModel extends Model
{
    use HasTranslateableModel;

    protected static ?string $translateablePackageKey = 'filament-tournament-league-administration::';
}
