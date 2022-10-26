<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions;

use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Traits\HasHideableLabel;

class DeleteAction extends ActionsDeleteAction
{
    use HasHideableLabel;
}
