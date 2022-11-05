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
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\DeleteGameScheduleAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DeleteEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\PeriodEndGameScheduleRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\PeriodStartGameScheduleRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\GameDaysRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\GamesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\PlayersRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\TeamsRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers\TotalTeamPointsRelationManager;
use Throwable;

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
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'federation_id')
                            )
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(
                                function (Closure $set) {
                                    $set('league_id', null);
                                }),

                        Select::make('league_id')
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

                                $recordFederationId = $record->federation?->id;

                                if ($federationId === null) {
                                    $set('federation_id', $recordFederationId);
                                    $federationId = $recordFederationId;
                                }

                                if ($recordFederationId === $federationId) {
                                    return $record->federation
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
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'league_id')
                            )
                            ->required()
                            ->reactive(),

                        DateTimePicker::make('started_at')
                            ->label(GameSchedule::transAttribute('started_at'))
                            ->firstDayOfWeek(1)
                            ->required()
                            ->rules([
                                fn (Closure $get) => new PeriodStartGameScheduleRule($get('ended_at')),
                            ]),

                        DateTimePicker::make('ended_at')
                            ->label(GameSchedule::transAttribute('ended_at'))
                            ->firstDayOfWeek(1)
                            ->required()
                            ->rules([
                                fn (Closure $get) => new PeriodEndGameScheduleRule($get('started_at')),
                            ]),

                        Select::make('game_days')
                            ->label(GameSchedule::transAttribute('game_days'))
                            ->options(collect()->times(50)->mapWithKeys(fn ($value) => [$value => $value]))
                            ->visible(fn (Page $livewire) => $livewire instanceof CreateRecord)
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'game_days')
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

                TextColumn::make('league.name')
                    ->label(Team::transAttribute('league_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('started_at')
                    ->label(GameSchedule::transAttribute('started_at'))
                    ->date()
                    ->toggleable(),

                TextColumn::make('ended_at')
                    ->label(GameSchedule::transAttribute('ended_at'))
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip()
                    ->using(function (GameSchedule $record): void {
                        try {
                            app(DeleteGameScheduleAction::class)->execute($record);
                        } catch (Throwable) {
                            DeleteEntryFailedNotification::make()->send();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GameDaysRelationManager::class,
            TeamsRelationManager::class,
            PlayersRelationManager::class,
            GamesRelationManager::class,
            TotalTeamPointsRelationManager::class,
        ];
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
        return parent::getGlobalSearchEloquentQuery()->with([
            'federation',
            'league',
            'days',
            'teams',
            'players',
            'totalTeamPoints.gameSchedule.federation.calculationType',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
