<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\RelationManagers\PlayersRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\RelationManagers\TeamsRelationManager;

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
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(League::transAttribute('name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::of($state)->slug())),

                        TextInput::make('slug')
                            ->label(League::transAttribute('slug'))
                            ->disabled()
                            ->required()
                            ->unique(League::class, 'slug', fn ($record) => $record),

                        Select::make('federation_id')
                            ->label(Federation::transAttribute('federation_id'))
                            ->validationAttribute(Federation::transAttribute('federation_id'))
                            ->relationship('federation', 'name')
                            ->options(Federation::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                Card::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label(League::transAttribute('created_at'))
                            ->content(fn (
                                ?League $record
                            ): string => $record ? $record->created_at->diffForHumans() : '-'),

                        Placeholder::make('updated_at')
                            ->label(League::transAttribute('created_at'))
                            ->content(fn (
                                ?League $record
                            ): string => $record ? $record->updated_at->diffForHumans() : '-'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(League::transAttribute('name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(League::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('federation.name')
                    ->label(League::transAttribute('federation.name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('federation.name')
                    ->label(League::transAttribute('federation.name'))
                    ->relationship('federation', 'name'),
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            PlayersRelationManager::class,
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['federation', 'teams.player', 'players']);
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
