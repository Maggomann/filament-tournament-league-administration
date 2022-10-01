<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Notifications\Notification;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameSchedule\Actions\SyncAllGameSchedulePlayersAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class PlayersRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'players';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.player', number: 2);
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
                    ->label('Alle Spieler aus den verknüpfen Teams verknüpfen')
                    ->button()
                    ->action(function (PlayersRelationManager $livewire): void {
                        try {
                            app(SyncAllGameSchedulePlayersAction::class)->execute(
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
                    ->recordSelectOptionsQuery(function (PlayersRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();

                        $titleColumnName = $livewire->getRecordTitleAttribute();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('team', fn ($query) => $query->whereIn('tournament_league_players.team_id', $relationship->getParent()->teams()->pluck('id')))
                            ->orderBy($titleColumnName);
                    }),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}
