<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\RelationManagers;

use Filament\Forms\Components\Checkbox;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\SyncAllGameHomePlayersAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntrySucceededNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Game;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class HomePlayersRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'homePlayers';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? TranslateComponent::tab(static::$translateablePackageKey, 'home_players');
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
                Action::make('syncAllHomePlayers')
                    ->label(TranslateComponent::buttonLlabel(static::$translateablePackageKey, 'link_all_home_players_from_the_linked_home_team'))
                    ->button()
                    ->action(function (HomePlayersRelationManager $livewire): void {
                        /** @var Game $game */
                        $game = $livewire->getRelationship()->getParent();

                        try {
                            app(SyncAllGameHomePlayersAction::class)->execute($game);

                            AttachEntrySucceededNotification::make()->send();
                        } catch (Throwable) {
                            AttachEntryFailedNotification::make()->send();
                        }
                    }),

                AttachAction::make()
                    ->form(function (AttachAction $action): array {
                        return [
                            $action->getRecordSelect(),
                            Checkbox::make('is_home')
                                ->label('home')
                                ->default(true)
                                ->disabled(true),
                        ];
                    })
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (HomePlayersRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();
                        $titleColumnName = $livewire->getRecordTitleAttribute();

                        /** @var Game $game */
                        $game = $relationship->getParent();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('team', fn ($query) => $query->where('tournament_league_players.team_id', $game->homeTeam->id))
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
