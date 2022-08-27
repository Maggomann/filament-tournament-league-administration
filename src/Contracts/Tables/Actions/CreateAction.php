<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions;

use Filament\Tables\Actions\CreateAction as ActionsCreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits\HasHideableLabel;

class CreateAction extends ActionsCreateAction
{
    use HasHideableLabel;
}
