<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TranslateableModel;

class CardTimestamps
{
    public static function make(TranslateableModel $model): Card
    {
        return Card::make()
            ->schema([
                Placeholder::make('created_at')
                    ->label($model::transAttribute('created_at'))
                    ->content(fn (
                        ?Model $record
                    ): string => $record ? $record->created_at->diffForHumans() : '-'),
                Placeholder::make('updated_at')
                    ->label($model::transAttribute('updated_at'))
                    ->content(fn (
                        ?Model $record
                    ): string => $record ? $record->updated_at->diffForHumans() : '-'),
            ])
            ->columnSpan(1);
    }
}
