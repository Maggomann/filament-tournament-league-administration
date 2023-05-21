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
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits\HasFileUpload;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\AddressesRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components\CardUploadAndTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\RelationManagers\TeamRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\SelectOptions\LeagueSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\PlayerResource\SelectOptions\TeamSelect;

class PlayerResource extends TranslateableResource
{
    use HasFileUpload;

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
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'federation_id')
                            )
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
                                return LeagueSelect::options($get, $set, $record);
                            })
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'league_id')
                            )
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (Closure $set) => $set('team_id', null)),

                        Select::make('team_id')
                            ->label(Player::transAttribute('team_id'))
                            ->validationAttribute(Player::transAttribute('team_id'))
                            ->options(function (Closure $get, Closure $set, ?Player $record) {
                                return TeamSelect::options($get, $set, $record);
                            })
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'team_id')
                            )
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

                        TextInput::make('email')
                            ->label(Player::transAttribute('email'))
                            ->validationAttribute(Player::transAttribute('email'))
                            ->nullable()
                            ->email()
                            ->unique(ignoreRecord: true),

                        TextInput::make('nickname')
                            ->label(Player::transAttribute('nickname'))
                            ->maxLength(255),

                        TextInput::make('id_number')
                            ->label(Player::transAttribute('id_number'))
                            ->maxLength(255),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                CardUploadAndTimestamps::make((new Player)),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getFileUploadColumn('Avatar'),

                TextColumn::make('name')
                    ->label(Player::transAttribute('name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(Player::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(Player::transAttribute('email'))
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
                EditAction::make()->onlyIconAndTooltip(),
                ViewAction::make()->onlyIconAndTooltip(),
                DeleteAction::make()->onlyIconAndTooltip(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeamRelationManager::class,
            AddressesRelationManager::class,
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
        return parent::getGlobalSearchEloquentQuery()->with(['team', 'league.federation.leagues', 'addresses']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
