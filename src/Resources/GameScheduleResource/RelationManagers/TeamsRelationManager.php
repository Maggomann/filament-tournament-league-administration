<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Notifications\Notification;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions\DetachGameScheduleTeamsAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions\SyncAllGameScheduleTeamsAction;
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
                    ->label('Alle Teams aus der Liga verknüpfen')
                    ->button()
                    ->action(function (TeamsRelationManager $livewire): void {
                        try {
                            app(SyncAllGameScheduleTeamsAction::class)->execute(
                                $livewire->getRelationship()->getParent()
                            );

                            Notification::make()
                                ->title(__('filament-support::actions/attach.single.messages.attached'))
                                ->success()
                                ->send();
                        } catch (Throwable $th) {
                            Notification::make()
                                ->title('Es ist ein Fehler beim Zuweisen der Datensätze aufgetreten')
                                ->danger()
                                ->send();

                            throw $th;
                        }
                    }),

                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (TeamsRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();

                        $titleColumnName = $livewire->getRecordTitleAttribute();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('league', fn ($query) => $query->where('tournament_league_teams.league_id', $relationship->getParent()->leagueBT->id))
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

                            Notification::make()
                                ->title(__('filament-support::actions/detach.multiple.messages.detached'))
                                ->success()
                                ->send();
                        } catch (Throwable $th) {
                            Notification::make()
                                ->title('Es ist ein Fehler beim Zuweisen der Datensätze aufgetreten')
                                ->danger()
                                ->send();

                            throw $th;
                        }
                    }),
            ]);
    }
}
