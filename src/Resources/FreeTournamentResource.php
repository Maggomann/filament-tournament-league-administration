<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources;

use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\PeriodEndedAtRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules\PeriodStartedAtRule;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits\HasContentEditor;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Traits\HasFileUpload;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers\EventLocalctionAddressRelationManager;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\Forms\Components\CardTimestamps;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\DartTypeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\ModeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\FreeTournamentResource\SelectOptions\QualificationLevelSelect;

class FreeTournamentResource extends TranslateableResource
{
    use HasContentEditor;
    use HasFileUpload;

    protected static ?string $model = FreeTournament::class;

    protected static ?string $slug = 'tournament-league/FreeTournaments';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(FreeTournament::transAttribute('name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Closure $set) => $set('slug', Str::of($state)->slug())),

                        TextInput::make('slug')
                            ->label(FreeTournament::transAttribute('slug'))
                            ->disabled()
                            ->required()
                            ->unique(FreeTournament::class, 'slug', fn ($record) => $record),

                        self::getContentEditor('description')
                            ->label(FreeTournament::transAttribute('description')),

                        Select::make('mode_id')
                            ->label(FreeTournament::transAttribute('mode_id'))
                            ->validationAttribute(FreeTournament::transAttribute('mode_id'))
                            ->options(fn () => ModeSelect::options())
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'mode_id')
                            )
                            ->preload()
                            ->required()
                            ->searchable(),

                        Select::make('dart_type_id')
                            ->label(FreeTournament::transAttribute('dart_type_id'))
                            ->validationAttribute(FreeTournament::transAttribute('dart_type_id'))
                            ->options(fn () => DartTypeSelect::options())
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'dart_type_id')
                            )
                            ->preload()
                            ->required()
                            ->searchable(),

                        Select::make('qualification_level_id')
                            ->label(FreeTournament::transAttribute('qualification_level_id'))
                            ->validationAttribute(FreeTournament::transAttribute('qualification_level_id'))
                            ->options(fn () => QualificationLevelSelect::options())
                            ->placeholder(
                                TranslateComponent::placeholder(static::$translateablePackageKey, 'qualification_level_id')
                            )
                            ->preload()
                            ->required()
                            ->searchable(),

                        TextInput::make('maximum_number_of_participants')
                            ->label(FreeTournament::transAttribute('maximum_number_of_participants'))
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10),

                        TextInput::make('coin_money')
                            ->label(FreeTournament::transAttribute('coin_money'))
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        KeyValue::make('prize_money_depending_on_placement')
                            ->keyLabel(FreeTournament::transAttribute('prize_money_depending_on_placement_key_value.key_label'))
                            ->valueLabel(FreeTournament::transAttribute('prize_money_depending_on_placement_key_value.value_label'))
                            ->label(FreeTournament::transAttribute('prize_money_depending_on_placement'))
                            ->required(),

                        DateTimePicker::make('started_at')
                            ->label(FreeTournament::transAttribute('started_at'))
                            ->firstDayOfWeek(1)
                            ->required()
                            ->rules([
                                fn (Closure $get) => new PeriodStartedAtRule($get('ended_at')),
                            ]),

                        DateTimePicker::make('ended_at')
                            ->label(FreeTournament::transAttribute('ended_at'))
                            ->firstDayOfWeek(1)
                            ->required()
                            ->rules([
                                fn (Closure $get) => new PeriodEndedAtRule($get('started_at')),
                            ]),

                        self::getFileUploadInput(),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                CardTimestamps::make((new FreeTournament)),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getFileUploadColumn('Logo'),

                TextColumn::make('name')
                    ->label(FreeTournament::transAttribute('name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(FreeTournament::transAttribute('slug'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
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
            EventLocalctionAddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFreeTournaments::route('/'),
            'create' => Pages\CreateFreeTournament::route('/create'),
            'edit' => Pages\EditFreeTournament::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['mode', 'dartType', 'qualificationLevel', 'address']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        return $details;
    }
}
