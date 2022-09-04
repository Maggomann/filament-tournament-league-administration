<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Forms\Components;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

class CardTimestamps
{
    public static function make(Model $model): Card
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
