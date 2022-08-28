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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource\RelationManagers\LeaguesRelationManager;

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
                            ->label(Federation::transAttribute('name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::of($state)->slug())),
                        TextInput::make('slug')
                            ->label(Federation::transAttribute('slug'))
                            ->disabled()
                            ->required()
                            ->unique(Federation::class, 'slug', fn ($record) => $record),
                        Select::make('calculation_type_id')
                            ->label(Federation::transAttribute('calculation_type_id'))
                            ->validationAttribute(Federation::transAttribute('calculation_type_id'))
                            ->relationship('calculationType', 'name')
                            ->options(CalculationType::all()->pluck('name', 'id'))
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
                            ->label(Federation::transAttribute('created_at'))
                            ->content(fn (
                                ?Federation $record
                            ): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Placeholder::make('updated_at')
                            ->label(Federation::transAttribute('created_at'))
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
                    ->label(Federation::transAttribute('name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(Federation::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('calculationType.name')
                    ->label(Federation::transAttribute('calculation_type_id'))
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (?Federation $record): string => $record ? $record->calculationType->description : '-'),
            ])
            ->filters([
                SelectFilter::make('calculation_type_id')
                    ->label(Federation::transAttribute('calculation_type_id'))
                    ->relationship('calculationType', 'name'),
            ])
            ->actions([
                EditAction::make()->hideLabelAndShowAsTooltip(),
                ViewAction::make()->hideLabelAndShowAsTooltip(),
                DeleteAction::make()->hideLabelAndShowAsTooltip(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LeaguesRelationManager::class,
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

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['calculationType']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
