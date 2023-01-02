<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\UpdateOrCreateTotalGameSchedulePointsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreatedEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\CreateEntrySuccessNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\TotalTeamPoint;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

/**
 * @method static GameSchedule getOwnerRecord()
 */
class TotalTeamPointsRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'totalTeamPoints';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.total_team_point', number: 1);
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        $recordTitleSingular = trans_choice(static::$translateablePackageKey.'filament-model.models.total_team_point', number: 1);

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
                TextColumn::make('placement')
                    ->label(TotalTeamPoint::transAttribute('placement')),

                TextColumn::make('team.name')
                    ->label(TotalTeamPoint::transAttribute('team_id')),

                TextColumn::make('total_number_of_encounters')
                    ->label(TotalTeamPoint::transAttribute('total_number_of_encounters')),

                TextColumn::make('total_wins')
                    ->label(TotalTeamPoint::transAttribute('total_wins')),

                TextColumn::make('total_defeats')
                    ->label(TotalTeamPoint::transAttribute('total_defeats')),

                TextColumn::make('total_draws')
                    ->label(TotalTeamPoint::transAttribute('total_draws')),

                TextColumn::make('total_victory_after_defeats')
                    ->label(TotalTeamPoint::transAttribute('total_victory_after_defeats'))
                    ->hidden(
                        fn (TotalTeamPointsRelationManager $livewire) => $livewire->getOwnerRecord()->federation->calculationType->id !== 2
                    ),

                TextColumn::make(TotalTeamPoint::transAttribute('legs'))
                    ->label(TotalTeamPoint::transAttribute('legs'))
                    ->getStateUsing(
                        fn (TotalTeamPoint $record): string => "{$record->total_home_points_legs} : {$record->total_guest_points_legs}"
                    ),

                TextColumn::make(TotalTeamPoint::transAttribute('games'))
                    ->label(TotalTeamPoint::transAttribute('games'))
                    ->getStateUsing(
                        fn (TotalTeamPoint $record): string => "{$record->total_home_points_games} : {$record->total_guest_points_games}"
                    ),

                TextColumn::make('total_points')
                    ->label(TotalTeamPoint::transAttribute('total_points')),

            ])
            ->filters([])
            ->headerActions([
                Action::make('recalculateGamePoints')
                    ->label(TranslateComponent::buttonLlabel(static::$translateablePackageKey, 'recalculation_of_the_game_plan_points'))
                    ->button()
                    ->action(function (TotalTeamPointsRelationManager $livewire): void {
                        try {
                            /** @var GameSchedule $gameSchedule */
                            $gameSchedule = $livewire->getRelationship()->getParent();

                            app(UpdateOrCreateTotalGameSchedulePointsAction::class)->execute($gameSchedule);

                            CreateEntrySuccessNotification::make()->send();
                        } catch (Throwable) {
                            CreatedEntryFailedNotification::make()->send();
                        }
                    }),
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
        return 'total_points';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
