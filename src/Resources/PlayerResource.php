<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Closure;
use Filament\Forms\Components\Card;
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
use Maggomann\FilamentTournamentLeagueAdministration\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\RelationManagers\TeamRelationManager;

class PlayerResource extends TranslateableResource
{
    protected static ?string $model = Player::class;

    protected static ?string $slug = 'tournament-league/players';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('federation_id')
                            ->label(League::transAttribute('federation_id'))
                            ->validationAttribute(League::transAttribute('federation_id'))
                            ->options(Federation::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(
                                function (Closure $set) {
                                    $set('league_id', null);
                                    $set('team_id', null);
                                }),

                        Select::make('league_id')
                            ->label(Team::transAttribute('league_id'))
                            ->validationAttribute(Team::transAttribute('league_id'))
                            ->options(function (Closure $get, Closure $set, ?Player $record) {
                                if (! $record) {
                                    return League::all()->pluck('name', 'id') ?? collect([]);
                                }

                                $federationId = $get('federation_id');

                                if ($federationId === null) {
                                    $federationId = $record->league?->federation?->id;

                                    $set('federation_id', $federationId);

                                    $options = $record
                                        ->league
                                        ?->federation
                                        ?->leagues
                                        ?->pluck('name', 'id');

                                    if ($options) {
                                        $set('league_id', $record->league->id);

                                        return $options;
                                    }
                                }

                                if ($federationId === null) {
                                    return League::all()->pluck('name', 'id') ?? collect([]);
                                }

                                return Federation::with('leagues')
                                    ->find($federationId)
                                    ?->leagues
                                    ?->pluck('name', 'id') ?? collect([]);
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (Closure $set) => $set('team_id', null)),

                        Select::make('team_id')
                            ->label(Player::transAttribute('team_id'))
                            ->validationAttribute(Player::transAttribute('team_id'))
                            ->options(Team::all()->pluck('name', 'id'))
                            ->options(function (Closure $get, Closure $set, ?Player $record) {
                                if (! $record) {
                                    return Team::all()->pluck('name', 'id') ?? collect([]);
                                }

                                $leagueId = $get('league_id');
                                $federationId = $get('federation_id');

                                if ($leagueId === null && $federationId) {
                                    return collect([]);
                                }

                                if ($leagueId === null) {
                                    $leagueId = $record->league?->id;

                                    $set('league_id', $leagueId);

                                    $options = League::with('teams')
                                        ->find($leagueId)
                                        ?->teams
                                        ?->pluck('name', 'id');

                                    if ($options) {
                                        $set('team_id', $record->team->id);

                                        return $options;
                                    }
                                }

                                if ($leagueId === null) {
                                    return Team::all()->pluck('name', 'id') ?? collect([]);
                                }

                                return League::with('teams')
                                    ->find($leagueId)
                                    ?->teams
                                    ?->pluck('name', 'id') ?? collect([]);
                            })
                            ->required()
                            ->searchable(),

                        TextInput::make('name')
                            ->label(Player::transAttribute('name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::of($state)->slug())),
                        TextInput::make('slug')
                            ->label(Player::transAttribute('slug'))
                            ->disabled()
                            ->required()
                            ->unique(Player::class, 'slug', fn ($record) => $record),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                CardTimestamps::make((new Player)),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(Player::transAttribute('name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(Player::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Team.name')
                    ->label(Player::transAttribute('team_id'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('team_id')
                    ->label(Player::transAttribute('team_id'))
                    ->relationship('team', 'name'),
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
            TeamRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['team', 'league.federation.leagues']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
