<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions;

use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Traits\HasHideableLabel;

class EditAction extends ActionsEditAction
{
    use HasHideableLabel;
}
