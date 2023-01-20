<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components;

use Filament\Forms\Components\Card;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits\HasFileUpload;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TranslateableModel;

class CardUploadAndTimestamps
{
    use HasFileUpload;

    public static function make(TranslateableModel $model): Card
    {
        return Card::make()
            ->schema(
                array_merge(
                    [self::getFileUploadInput()],
                    PlaceholderTimestamps::execute($model)
                ))
            ->columnSpan(1);
    }
}
