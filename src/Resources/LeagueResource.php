<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

class LeagueResource extends TranslateableResource
{
    protected static ?string $model = League::class;

    protected static ?string $slug = 'tournament-league/leagues';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeagues::route('/'),
            'create' => Pages\CreateLeague::route('/create'),
            'edit' => Pages\EditLeague::route('/{record}/edit'),
        ];
    }
}
