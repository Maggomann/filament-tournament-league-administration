<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\DeleteAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameEncounter;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\SelectOptions\GameEncounterTypeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;

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

                Select::make('game_encounter_type_id')
                    ->label(GameEncounter::transAttribute('game_encounter_type_id'))
                    ->options(fn () => GameEncounterTypeSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'game_encounter_type_id')
                    )
                    ->preload()
                    ->required()
                    ->searchable(),

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
                CreateAction::make(),
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
