<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource\Pages;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;

class FederationResource extends TranslateableResource
{
    protected static ?string $model = Federation::class;

    protected static ?string $slug = 'tournament-league/federations';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::of($state)->slug())),
                        TextInput::make('slug')
                            ->disabled()
                            ->required()
                            ->unique(Federation::class, 'slug', fn ($record) => $record),
                        Select::make('caluclation_type_id')
                            ->label('Kalkulationstyp')
                            ->relationship('calculationType', 'name')
                            ->options(CalculationType::all()->pluck('name', 'id'))
                            ->searchable()
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                Card::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (
                                ?Federation $record
                            ): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (
                                ?Federation $record
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('calculationType.name')
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (?Federation $record): string => $record ? $record->calculationType->description : '-'),

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
            'index' => Pages\ListFederation::route('/'),
            'create' => Pages\CreateFederation::route('/create'),
            'edit' => Pages\EditFederation::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
