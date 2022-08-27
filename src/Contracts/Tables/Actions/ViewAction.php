<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions;

use Filament\Tables\Actions\ViewAction as ActionsViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits\HasHideableLabel;

class ViewAction extends ActionsViewAction
{
    use HasHideableLabel;
}
