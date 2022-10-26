<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions;

use Filament\Tables\Actions\ViewAction as ActionsViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Traits\HasHideableLabel;

class ViewAction extends ActionsViewAction
{
    use HasHideableLabel;
}
