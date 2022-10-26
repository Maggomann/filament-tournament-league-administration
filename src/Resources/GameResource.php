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
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\GameEndetAtRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\GameStartedAtRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GameDaySelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GuestTeamSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\HomeTeamSelect;

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
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set) {
                                        $set('game_day_id', null);
                                        $set('home_team_id', null);
                                        $set('guest_team_id', null);
                                    }),

                                Select::make('game_day_id')
                                    ->label(Game::transAttribute('game_day_id'))
                                    ->validationAttribute(Game::transAttribute('game_day_id'))
                                    ->options(function (Closure $get, ?Game $record) {
                                        return GameDaySelect::options($get, $record);
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
                                    ->options(function (Closure $get, ?Game $record) {
                                        return HomeTeamSelect::options($get, $record);
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
                                    ->options(function (Closure $get, ?Game $record) {
                                        return GuestTeamSelect::options($get, $record);
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
                                    ->required()
                                    ->default(0)
                                    ->numeric(),

                                TextInput::make('guest_points_legs')
                                    ->label(Game::transAttribute('guest_points_legs'))
                                    ->required()
                                    ->default(0)
                                    ->numeric(),

                                TextInput::make('home_points_games')
                                    ->label(Game::transAttribute('home_points_games'))
                                    ->required()
                                    ->default(0)
                                    ->numeric(),

                                TextInput::make('guest_points_games')
                                    ->label(Game::transAttribute('guest_points_games'))
                                    ->required()
                                    ->default(0)
                                    ->numeric(),

                            ]),
                        Tab::make(TranslateComponent::tab(static::$translateablePackageKey, 'points_after_overtime'))
                            ->icon('heroicon-o-scale')
                            ->schema([
                                Toggle::make('has_an_overtime')
                                    ->label(Game::transAttribute('has_an_overtime'))
                                    ->reactive()
                                    ->columnSpan(2),

                                TextInput::make('home_points_after_draw')
                                    ->label(Game::transAttribute('home_points_after_draw'))
                                    ->default(0)
                                    ->disabled(fn (Closure $get) => ($get('has_an_overtime') === true) ? false : true)
                                    ->required(fn (Closure $get) => $get('has_an_overtime'))
                                    ->numeric(),

                                TextInput::make('guest_points_after_draw')
                                    ->label(Game::transAttribute('guest_points_after_draw'))
                                    ->default(0)
                                    ->disabled(fn (Closure $get) => ($get('has_an_overtime') === true) ? false : true)
                                    ->required(fn (Closure $get) => $get('has_an_overtime'))
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
                TextColumn::make('gameSchedule.name')
                    ->label(Game::transAttribute('game_schedule_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gameDay.day')
                    ->label(Game::transAttribute('game_day_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('homeTeam.name')
                    ->label(Game::transAttribute('home_team_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guestTeam.name')
                    ->label(Game::transAttribute('guest_team_id'))
                    ->searchable()
                    ->sortable(),

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
                DeleteAction::make()->hideLabellnTooltip(),
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
            'gameSchedule',
            'gameDay',
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
