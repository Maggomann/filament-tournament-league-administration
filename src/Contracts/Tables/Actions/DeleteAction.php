<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions;

use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits\HasHideableLabel;

class DeleteAction extends ActionsDeleteAction
{
    use HasHideableLabel;
}
