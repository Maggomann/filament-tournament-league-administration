<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use App\Models\League;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;

class LeagueResource extends Resource
{
    protected static ?string $model = League::class;

    protected static ?string $slug = 'tournament-league/leagues';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Tournament & League';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 0;

    public static function getLabel(): string
    {
        return 'TestLabel League';
    }

    protected static function getNavigationGroup(): ?string
    {
        return 'Tournament and Leagues';
    }

    public static function getPluralLabel(): string
    {
        return 'TestLabel Leagues';
    }

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
