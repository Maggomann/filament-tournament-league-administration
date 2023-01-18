<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions;

use Filament\Tables\Actions\CreateAction as ActionsCreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Traits\HasIconLabel;

class CreateAction extends ActionsCreateAction
{
    use HasIconLabel;
}
