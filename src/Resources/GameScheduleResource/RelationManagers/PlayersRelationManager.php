<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\GameSchedule\Actions\SyncAllGameSchedulePlayersAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntrySucceededNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;
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
                    ->label(TranslateComponent::buttonLlabel(static::$translateablePackageKey, 'link_all_players_from_the_linked_teams'))
                    ->button()
                    ->action(function (PlayersRelationManager $livewire): void {
                        /** @var GameSchedule $gameSchedule */
                        $gameSchedule = $livewire->getRelationship()->getParent();

                        try {
                            app(SyncAllGameSchedulePlayersAction::class)->execute($gameSchedule);

                            AttachEntrySucceededNotification::make()->send();
                        } catch (Throwable) {
                            AttachEntryFailedNotification::make()->send();
                        }
                    }),

                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (PlayersRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();
                        $titleColumnName = $livewire->getRecordTitleAttribute();

                        /** @var GameSchedule $gameSchedule */
                        $gameSchedule = $relationship->getParent();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('team', fn ($query) => $query->whereIn('tournament_league_players.team_id', $gameSchedule->teams()->pluck('id')))
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
