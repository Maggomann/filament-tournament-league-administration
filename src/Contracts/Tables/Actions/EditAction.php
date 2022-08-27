<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions;

use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits\HasHideableLabel;

class EditAction extends ActionsEditAction
{
    use HasHideableLabel;
}
