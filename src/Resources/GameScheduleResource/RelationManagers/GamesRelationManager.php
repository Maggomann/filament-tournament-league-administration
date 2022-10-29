<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;

class GamesRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'games';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.game', number: 2);
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        $recordTitleSingular = trans_choice(static::$translateablePackageKey.'filament-model.models.game', number: 1);

        if (! $record) {
            return $recordTitleSingular;
        }

        $recordTitle = $recordTitleSingular;
        $recordTitle .= ' ';

        $recordTitle .= $record->getAttributeValue(static::getRecordTitleAttribute());

        return $recordTitle;
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                TextColumn::make(TotalTeamPoint::transAttribute('legs'))
                    ->label(TotalTeamPoint::transAttribute('legs'))
                    ->getStateUsing(
                        fn (Game $record): string => "{$record->home_points_legs} : {$record->guest_points_legs}"
                    ),

                TextColumn::make(TotalTeamPoint::transAttribute('games'))
                    ->label(TotalTeamPoint::transAttribute('games'))
                    ->getStateUsing(
                        fn (Game $record): string => "{$record->home_points_games} : {$record->guest_points_games}"
                    ),

                TextColumn::make(TotalTeamPoint::transAttribute('points_after_draws'))
                    ->label(TotalTeamPoint::transAttribute('points_after_draws'))
                    ->getStateUsing(
                        fn (Game $record): string => "{$record->home_points_after_draw} : {$record->guest_points_after_draw}"
                    )
                    ->hidden(
                        fn (GamesRelationManager $livewire) => $livewire->getOwnerRecord()->federation->calculationType->id !== 2
                    ),

                TextColumn::make('started_at')
                    ->label(Game::transAttribute('started_at'))
                    ->date()
                    ->toggleable(),

                TextColumn::make('ended_at')
                    ->label(Game::transAttribute('ended_at'))
                    ->date()
                    ->toggleable(),

            ])
            ->filters([])
            ->headerActions([
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'game_day_id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }
}
