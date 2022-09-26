<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\TranslatedSelectOption;
use Maggomann\FilamentTournamentLeagueAdministration\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;

class GameScheduleResource extends TranslateableResource
{
    protected static ?string $model = GameSchedule::class;

    protected static ?string $slug = 'tournament-league/game-schedules';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(GameSchedule::transAttribute('name'))
                            ->columnSpan(2)
                            ->required(),

                        Select::make('federation_id')
                            ->label(League::transAttribute('federation_id'))
                            ->validationAttribute(League::transAttribute('federation_id'))
                            ->options(Federation::all()->pluck('name', 'id'))
                            ->placeholder(
                                TranslatedSelectOption::placeholder(static::$translateablePackageKey.'translations.forms.components.select.placeholder.federation_id')
                            )
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(
                                function (Closure $set) {
                                    $set('leagueable_id', null);
                                }),

                        Select::make('leagueable_id')
                            ->label(Team::transAttribute('league_id'))
                            ->validationAttribute(Team::transAttribute('league_id'))
                            ->options(function (Closure $get, Closure $set, ?GameSchedule $record) {
                                $federationId = $get('federation_id');

                                if (! $record) {
                                    if (! $federationId) {
                                        return collect([]);
                                    }

                                    return Federation::with('leagues')
                                        ->find($federationId)
                                        ?->leagues
                                        ?->pluck('name', 'id') ?? collect([]);
                                }

                                $recordFederationId = $record?->federation?->id;

                                if ($federationId === null) {
                                    $set('federation_id', $recordFederationId);
                                    $federationId = $recordFederationId;
                                }

                                if ($recordFederationId === $federationId) {
                                    return $record?->federation
                                        ?->leagues
                                        ?->pluck('name', 'id')
                                        ?? collect([]);
                                }

                                return Federation::with('leagues')
                                    ->find($federationId)
                                    ?->leagues
                                    ?->pluck('name', 'id') ?? collect([]);
                            })
                            ->placeholder(
                                TranslatedSelectOption::placeholder(static::$translateablePackageKey.'translations.forms.components.select.placeholder.league_id')
                            )
                            ->required()
                            ->reactive(),

                        DateTimePicker::make('period_start')
                            ->label(GameSchedule::transAttribute('period_start'))
                            ->firstDayOfWeek(1)
                            ->required(),

                        DateTimePicker::make('period_end')
                            ->label(GameSchedule::transAttribute('period_end'))
                            ->firstDayOfWeek(1)
                            ->required(),

                        Select::make('game_days')
                            ->label(GameSchedule::transAttribute('game_days'))
                            ->options(collect()->times(50)->mapWithKeys(fn ($value, $key) => [$value => $value]))
                            ->visible(fn (Page $livewire) => $livewire instanceof CreateRecord)
                            ->placeholder(
                                TranslatedSelectOption::placeholder(static::$translateablePackageKey.'translations.forms.components.select.placeholder.game_days')
                            )
                           ->searchable(),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                CardTimestamps::make((new GameSchedule)),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(GameSchedule::transAttribute('name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('federation.name')
                    ->label(League::transAttribute('federation_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('leagueBT.name')
                    ->label(Team::transAttribute('league_id'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('period_start')
                    ->label(GameSchedule::transAttribute('period_start'))
                    ->date()
                    ->toggleable(),

                TextColumn::make('period_end')
                    ->label(GameSchedule::transAttribute('period_end'))
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameSchedules::route('/'),
            'create' => Pages\CreateGameSchedule::route('/create'),
            'edit' => Pages\EditGameSchedule::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['federation', 'league']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
