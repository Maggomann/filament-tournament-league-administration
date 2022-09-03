<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Maggomann\FilamentTournamentLeagueAdministration\Forms\Components\CardTimestamps;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Federation;
use Maggomann\FilamentTournamentLeagueAdministration\Models\League;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TeamResource\RelationManagers\PlayersRelationManager;
use Throwable;

class TeamResource extends TranslateableResource
{
    protected static ?string $model = Team::class;

    protected static ?string $slug = 'tournament-league/teams';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

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
                            ->reactive()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                Select::make('calculation_type_id')
                                    ->label(Federation::transAttribute('calculation_type_id'))
                                    ->validationAttribute(Federation::transAttribute('calculation_type_id'))
                                    ->options(CalculationType::all()->pluck('name', 'id'))
                                    ->exists(table: CalculationType::class, column: 'id')
                                    ->required()
                                    ->searchable(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                $createTitle = __('filament::resources/pages/create-record.title', [
                                    'label' => FederationResource::getModelLabel(),
                                ]);

                                return $action
                                    ->modalHeading($createTitle)
                                    ->modalButton($createTitle)
                                    ->modalWidth('lg');
                            })
                            ->createOptionUsing(static function (Select $component, array $data) {
                                try {
                                    // TODO: In Action auslagern:
                                    $federation = new Federation();
                                    $federation->fill($data);
                                    $federation->calculation_type_id = Arr::get($data, 'calculation_type_id');
                                    $federation->save();

                                    $component->options(Federation::all()->pluck('name', 'id'));

                                    // TODO: Notifaction bei Error
                                    Notification::make()
                                        ->title(__('filament::resources/pages/create-record.messages.created'))
                                        ->success()
                                        ->send();

                                    return $federation->getKey();
                                } catch (Throwable $th) {
                                    Notification::make()
                                        ->title('Es ist ein Fehler beim Erstellen des Datensetzes aufgetrteten')
                                        ->danger()
                                        ->send();
                                }
                            })
                            ->afterStateUpdated(fn ($state, Closure $set) => $set('league_id', null)),

                        Select::make('league_id')
                            ->label(Team::transAttribute('league_id'))
                            ->validationAttribute(Team::transAttribute('league_id'))
                            ->options(function (Closure $get, Closure $set, ?Team $record) {
                                $federationId = $get('federation_id');

                                if ($record && $federationId === null) {
                                    $federationId = $record->league?->federation?->id;

                                    $set('federation_id', $federationId );
                                }

                                if ($federationId === null) {
                                    return League::all()->pluck('name', 'id');
                                }

                                return Federation::with('leagues')
                                    ->find($federationId)
                                    ?->leagues
                                    ?->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable(),

                        TextInput::make('name')
                            ->label(Team::transAttribute('name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Closure $set) => $set('slug', Str::of($state)->slug())),

                        TextInput::make('slug')
                            ->label(Team::transAttribute('slug'))
                            ->disabled()
                            ->required()
                            ->unique(Team::class, 'slug', fn ($record) => $record),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                    CardTimestamps::make((new Team)),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(Team::transAttribute('name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(Team::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('league.name')
                    ->label(Team::transAttribute('league_id'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('league_id')
                    ->label(Team::transAttribute('league_id'))
                    ->relationship('league', 'name'),
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
            PlayersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['league.federation', 'players']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
