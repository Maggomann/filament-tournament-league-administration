<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components;

use Filament\Forms\Components\Card;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TranslateableModel;

class CardTimestamps
{
    public static function make(TranslateableModel $model): Card
    {
        return Card::make()
            ->schema(PlaceholderTimestamps::execute($model))
            ->columnSpan(1);
    }
}
