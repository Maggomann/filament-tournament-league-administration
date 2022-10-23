<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\LeagueResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;

class PlayersRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'players';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('team_id')
                    ->label(Player::transAttribute('team_id'))
                    ->validationAttribute(Player::transAttribute('team_id'))
                    ->relationship('team', 'name')
                    ->options(Team::all()->pluck('name', 'id'))
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'team_id')
                    )
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('team.name')
                    ->label(Player::transAttribute('team_id'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('team_id')
                    ->label(Player::transAttribute('team_id'))
                    ->relationship('team', 'name'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
