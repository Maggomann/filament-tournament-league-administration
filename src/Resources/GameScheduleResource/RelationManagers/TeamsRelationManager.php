<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\DetachGameScheduleTeamsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\SyncAllGameScheduleTeamsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntrySucceededNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DetachBuldEntriesFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DetachBuldEntriesSucceededNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class TeamsRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'teams';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.team', number: 2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(Team::transAttribute('name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('syncAllTeams')
                    ->label(TranslateComponent::buttonLlabel(static::$translateablePackageKey, 'link_all_teams_in_the_league'))
                    ->button()
                    ->action(function (TeamsRelationManager $livewire): void {
                        try {
                            /** @var GameSchedule $gameSchedule */
                            $gameSchedule = $livewire->getRelationship()->getParent();

                            app(SyncAllGameScheduleTeamsAction::class)->execute($gameSchedule);

                            AttachEntrySucceededNotification::make()->send();
                        } catch (Throwable) {
                            AttachEntryFailedNotification::make()->send();
                        }
                    }),

                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (TeamsRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();
                        $titleColumnName = $livewire->getRecordTitleAttribute();
                        /** @var GameSchedule $gameSchedule */
                        $gameSchedule = $relationship->getParent();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('league', fn ($query) => $query->where('tournament_league_teams.league_id', $gameSchedule->league->id))
                            ->orderBy($titleColumnName);
                    }),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->action(function (TeamsRelationManager $livewire, Collection $records): void {
                        try {
                            app(DetachGameScheduleTeamsAction::class)->execute($livewire, $records);

                            DetachBuldEntriesSucceededNotification::make()->send();
                        } catch (Throwable) {
                            DetachBuldEntriesFailedNotification::make()->send();
                        }
                    }),
            ]);
    }
}
