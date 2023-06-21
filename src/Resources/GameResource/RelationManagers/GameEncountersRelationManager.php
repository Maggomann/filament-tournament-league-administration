<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\RelationManagers;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\UpdateOrCreateGameEncounterAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\DTO\GameEncounterData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GameEncounterTypeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GuestPlayerOneSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GuestPlayerTwoSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\HomePlayerOneSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\HomePlayerTwoSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class GameEncountersRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'gameEncounters';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.game_encounter', number: 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('game_id')
                    ->relationship('game', 'id')
                    ->label(GameEncounter::transAttribute('game_id'))
                    ->default(fn (GameEncountersRelationManager $livewire) => $livewire->getOwnerRecord()->id)
                    ->disabled(),

                TextInput::make('order')
                    ->label(GameEncounter::transAttribute('order'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                Select::make('game_encounter_type_id')
                    ->label(GameEncounter::transAttribute('game_encounter_type_id'))
                    ->options(fn () => GameEncounterTypeSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'game_encounter_type_id')
                    )
                    ->preload()
                    ->required()
                    ->columnSpan(2)
                    ->searchable(),

                Select::make('home_player_id_1')
                    ->label(GameEncounter::transAttribute('home_player_id_1'))
                    ->validationAttribute(GameEncounter::transAttribute('home_player_id_1'))
                    ->options(function (Closure $get, Closure $set, ?GameEncounter $record) {
                        return HomePlayerOneSelect::options($get, $set, $record);
                    })
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'home_player_id')
                    )
                    ->required()
                    ->searchable()
                    ->reactive(),

                Select::make('guest_player_id_1')
                    ->label(GameEncounter::transAttribute('guest_player_id_1'))
                    ->validationAttribute(GameEncounter::transAttribute('guest_player_id_1'))
                    ->options(function (Closure $get, Closure $set, ?GameEncounter $record) {
                        return GuestPlayerOneSelect::options($get, $set, $record);
                    })
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'guest_player_id_1')
                    )
                    ->required()
                    ->searchable()
                    ->reactive(),

                Select::make('home_player_id_2')
                    ->label(GameEncounter::transAttribute('home_player_id_2'))
                    ->validationAttribute(GameEncounter::transAttribute('home_player_id_2'))
                    ->options(function (Closure $get, Closure $set, ?GameEncounter $record) {
                        return HomePlayerTwoSelect::options($get, $set, $record);
                    })
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'home_player_id')
                    )
                    ->required()
                    ->searchable()
                    ->reactive(),

                Select::make('guest_player_id_2')
                    ->label(GameEncounter::transAttribute('guest_player_id_2'))
                    ->validationAttribute(GameEncounter::transAttribute('guest_player_id_2'))
                    ->options(function (Closure $get, Closure $set, ?GameEncounter $record) {
                        return GuestPlayerTwoSelect::options($get, $set, $record);
                    })
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'guest_player_id_2')
                    )
                    ->required()
                    ->searchable()
                    ->reactive(),

                TextInput::make('home_team_win')
                    ->label(GameEncounter::transAttribute('home_team_win'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                TextInput::make('guest_team_win')
                    ->label(GameEncounter::transAttribute('guest_team_win'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                TextInput::make('home_team_defeat')
                    ->label(GameEncounter::transAttribute('home_team_defeat'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                TextInput::make('guest_team_defeat')
                    ->label(GameEncounter::transAttribute('guest_team_defeat'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                TextInput::make('home_team_points_leg')
                    ->label(GameEncounter::transAttribute('home_team_points_leg'))
                    ->required()
                    ->default(0)
                    ->numeric(),

                TextInput::make('guest_team_points_leg')
                    ->label(GameEncounter::transAttribute('guest_team_points_leg'))
                    ->required()
                    ->default(0)
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('game_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (HasRelationshipTable $livewire, array $data) {
                        try {
                            /** @var GameEncounter $gameEncounter */
                            $gameEncounter = $livewire->getRelationship()->getModel();

                            /** @var Game $game */
                            $game = $livewire->getRelationship()->getParent();

                            return app(UpdateOrCreateGameEncounterAction::class)->execute(
                                $game,
                                GameEncounterData::from($data),
                                $gameEncounter
                            );
                        } catch (Throwable) {
                            CreatedEntryFailedNotification::make()->send();
                        }
                    }),
            ])
            ->actions([
                EditAction::make()->onlyIconAndTooltip(),
                ViewAction::make()->onlyIconAndTooltip(),
                DeleteAction::make()->onlyIconAndTooltip(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
