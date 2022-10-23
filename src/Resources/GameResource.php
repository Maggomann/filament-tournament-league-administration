<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Rules\GameEndetAtRule;
use Maggomann\FilamentTournamentLeagueAdministration\Rules\GameStartedAtRule;

class GameResource extends TranslateableResource
{
    protected static ?string $model = Game::class;

    protected static ?string $slug = 'tournament-league/games';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Heading')
                    ->tabs([
                        Tab::make(TranslateComponent::tab(static::$translateablePackageKey, 'game_schedule'))
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Select::make('game_schedule_id')
                                    ->relationship('gameSchedule', 'name')
                                    ->label(Game::transAttribute('game_schedule_id'))
                                    ->placeholder(
                                        TranslateComponent::placeholder(static::$translateablePackageKey, 'game_schedule_id')
                                    )
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set) {
                                        $set('game_day_id', null);
                                        $set('home_team_id', null);
                                        $set('guest_team_id', null);
                                    }),

                                Select::make('game_day_id')
                                    ->label(Game::transAttribute('game_day_id'))
                                    ->validationAttribute(Game::transAttribute('game_day_id'))
                                    ->options(function (Closure $get, Closure $set, ?Game $record) {
                                        $gameScheduleId = $get('game_schedule_id');

                                        if (! $record) {
                                            if (! $gameScheduleId) {
                                                return collect([]);
                                            }

                                            $collection = GameSchedule::with('days')
                                                ->find($gameScheduleId)
                                                ?->days;

                                            if ($collection) {
                                                return $collection->mapWithKeys(fn (GameDay $gameDay) => [
                                                    $gameDay->id => "{$gameDay->day}  - ({$gameDay->started_at} - {$gameDay->ended_at})",
                                                ]);
                                            }

                                            return collect([]);
                                        }

                                        $recordGameScheduleById = $record->gameSchedule?->id;

                                        if ($recordGameScheduleById === $gameScheduleId) {
                                            $collection = $record->gameSchedule
                                                ?->days;

                                            if ($collection) {
                                                return $collection->mapWithKeys(fn (GameDay $gameDay) => [
                                                    $gameDay->id => "{$gameDay->day}  - ({$gameDay->started_at} - {$gameDay->ended_at})",
                                                ]);
                                            }

                                            return collect([]);
                                        }

                                        $collection = GameSchedule::with('days')
                                                ->find($gameScheduleId)
                                                ?->days;

                                        if ($collection) {
                                            return $collection->mapWithKeys(fn (GameDay $gameDay) => [
                                                $gameDay->id => "{$gameDay->day}  - ({$gameDay->started_at} - {$gameDay->ended_at})",
                                            ]);
                                        }

                                        return collect([]);
                                    })
                                    ->placeholder(
                                        TranslateComponent::placeholder(static::$translateablePackageKey, 'game_day_id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive(),

                                DateTimePicker::make('started_at')
                                    ->label(Game::transAttribute('started_at'))
                                    ->firstDayOfWeek(1)
                                    ->required()
                                    ->rules([
                                        fn (Closure $get) => new GameStartedAtRule($get('ended_at'), $get('game_day_id')),
                                    ]),

                                DateTimePicker::make('ended_at')
                                    ->label(Game::transAttribute('ended_at'))
                                    ->firstDayOfWeek(1)
                                    ->required()
                                    ->rules([
                                        fn (Closure $get) => new GameEndetAtRule($get('started_at'), $get('game_day_id')),
                                    ]),
                            ]),
                        Tab::make(TranslateComponent::tab(static::$translateablePackageKey, 'teams'))
                            ->icon('heroicon-o-users')
                            ->schema([
                                Select::make('home_team_id')
                                    ->label(Game::transAttribute('home_team_id'))
                                    ->validationAttribute(Game::transAttribute('home_team_id'))
                                    ->options(function (Closure $get, Closure $set, ?Game $record) {
                                        $gameScheduleId = $get('game_schedule_id');

                                        if (! $record) {
                                            if (! $gameScheduleId) {
                                                return collect([]);
                                            }

                                            return GameSchedule::with('teams')
                                                ->find($gameScheduleId)
                                                ?->teams
                                                ?->pluck('name', 'id') ?? collect([]);
                                        }

                                        $recordGameScheduleById = $record->gameSchedule?->id;

                                        if ($recordGameScheduleById === $gameScheduleId) {
                                            return $record->gameSchedule
                                                ?->teams
                                                ?->pluck('name', 'id')
                                                ?? collect([]);
                                        }

                                        return GameSchedule::with('teams')
                                            ->find($gameScheduleId)
                                            ?->teams
                                            ?->pluck('name', 'id') ?? collect([]);
                                    })
                                    ->placeholder(
                                        TranslateComponent::placeholder(static::$translateablePackageKey, 'home_team_id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive(),

                                Select::make('guest_team_id')
                                    ->label(Game::transAttribute('guest_team_id'))
                                    ->validationAttribute(Game::transAttribute('guest_team_id'))
                                    ->options(function (Closure $get, Closure $set, ?Game $record) {
                                        $gameScheduleId = $get('game_schedule_id');

                                        if (! $record) {
                                            if (! $gameScheduleId) {
                                                return collect([]);
                                            }

                                            return GameSchedule::with('teams')
                                                ->find($gameScheduleId)
                                                ?->teams
                                                ?->pluck('name', 'id') ?? collect([]);
                                        }

                                        $recordGameScheduleById = $record->gameSchedule?->id;

                                        if ($recordGameScheduleById === $gameScheduleId) {
                                            return $record->gameSchedule
                                                ?->teams
                                                ?->pluck('name', 'id')
                                                ?? collect([]);
                                        }

                                        return GameSchedule::with('teams')
                                            ->find($gameScheduleId)
                                            ?->teams
                                            ?->pluck('name', 'id') ?? collect([]);
                                    })
                                    ->placeholder(
                                        TranslateComponent::placeholder(static::$translateablePackageKey, 'guest_team_id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive(),

                            ]),
                        Tab::make(TranslateComponent::tab(static::$translateablePackageKey, 'points'))
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                TextInput::make('home_points_legs')
                                    ->label(Game::transAttribute('home_points_legs'))
                                    ->numeric(),

                                TextInput::make('guest_points_legs')
                                    ->label(Game::transAttribute('guest_points_legs'))
                                    ->numeric(),

                                TextInput::make('home_points_games')
                                    ->label(Game::transAttribute('home_points_games'))
                                    ->numeric(),

                                TextInput::make('guest_points_games')
                                    ->label(Game::transAttribute('guest_points_games'))
                                    ->numeric(),

                            ]),
                        Tab::make(TranslateComponent::tab(static::$translateablePackageKey, 'points_after_overtime'))
                            ->icon('heroicon-o-scale')
                            ->schema([
                                Toggle::make('has_an_overtime')
                                    ->label(Game::transAttribute('has_an_overtime'))
                                    ->columnSpan(2),

                                TextInput::make('home_points_after_draw')
                                    ->label(Game::transAttribute('home_points_after_draw'))
                                    ->numeric(),

                                TextInput::make('guest_points_after_draw')
                                    ->label(Game::transAttribute('guest_points_after_draw'))
                                    ->numeric(),
                            ]),
                    ])->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2)
                    ->activeTab(1),
                CardTimestamps::make((new Game)),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('started_at')
                    ->label(Game::transAttribute('started_at'))
                    ->date()
                    ->toggleable(),

                TextColumn::make('ended_at')
                    ->label(Game::transAttribute('ended_at'))
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'GameSchedule',
            'ganeDay',
            'homeTeam',
            'guestTeam',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
