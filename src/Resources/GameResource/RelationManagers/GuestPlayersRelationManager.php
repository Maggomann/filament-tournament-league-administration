<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameResource\RelationManagers;

use Filament\Forms\Components\Checkbox;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Actions\SyncAllGameGuestPlayersAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\AttachEntrySucceededNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Team;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class GuestPlayersRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'guestPlayers';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? TranslateComponent::tab(static::$translateablePackageKey, 'guest_players');
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
                Action::make('syncAllGuestPlayers')
                    ->label(TranslateComponent::buttonLlabel(static::$translateablePackageKey, 'link_all_guest_players_from_the_linked_guest_team'))
                    ->button()
                    ->action(function (GuestPlayersRelationManager $livewire): void {
                        /** @var Game $game */
                        $game = $livewire->getRelationship()->getParent();

                        try {
                            app(SyncAllGameGuestPlayersAction::class)->execute($game);

                            AttachEntrySucceededNotification::make()->send();
                        } catch (Throwable) {
                            AttachEntryFailedNotification::make()->send();
                        }
                    }),

                AttachAction::make()
                    ->form(function (AttachAction $action): array {
                        return [
                            $action->getRecordSelect(),
                            Checkbox::make('is_guest')
                                ->label('guest')
                                ->default(true)
                                ->disabled(true),
                        ];
                    })
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (GuestPlayersRelationManager $livewire) {
                        $relationship = $livewire->getRelationship();
                        $titleColumnName = $livewire->getRecordTitleAttribute();

                        /** @var Game $game */
                        $game = $relationship->getParent();

                        return $relationship
                            ->getRelated()
                            ->query()
                            ->whereHas('team', fn ($query) => $query->where('tournament_league_players.team_id', $game->guestTeam->id))
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
