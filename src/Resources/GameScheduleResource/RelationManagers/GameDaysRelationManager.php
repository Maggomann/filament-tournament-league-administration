<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\RelationManagers;

use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\Actions\UpdateGameDayAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\GameDay\DTO\GameDayData;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\CreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\GameScheduleResource\Pages;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Throwable;

class GameDaysRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'days';

    protected static ?string $recordTitleAttribute = 'day';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.day', number: 2);
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        $recordTitleSingular = trans_choice(static::$translateablePackageKey.'filament-model.models.day', number: 1);

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
        return $form
            ->schema([
                Select::make('game_schedule_id')
                    ->relationship('gameSchedule', 'name')
                    ->label(GameDay::transAttribute('game_schedule_id'))
                    ->default(fn (GameDaysRelationManager $livewire) => $livewire->getOwnerRecord()->id)
                    ->disabled(),

                TextInput::make('day')
                    ->label(GameDay::transAttribute('day'))
                    ->disabled(fn (?Model $record) => $record instanceof GameDay)
                    ->minValue(1)
                    ->integer(true)
                    ->required()
                    ->rules([
                        // TODO: In Validatioklasse auslagern
                        function ($livewire) {
                            return function (string $attribute, $value, Closure $fail) use ($livewire) {
                                if ($livewire->getOwnerRecord()
                                    ?->days()
                                    ?->where('day', $value)
                                    ?->exists()
                                ) {
                                    // TODO: translation
                                    $fail("Der Wert {$value} ist bereits vorhanden.");
                                }
                            };
                        },
                    ]),

                DateTimePicker::make('start')
                    ->label(GameDay::transAttribute('start'))
                    ->firstDayOfWeek(1)
                    ->required(),

                DateTimePicker::make('end')
                    ->label(GameDay::transAttribute('end'))
                    ->firstDayOfWeek(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day')
                    ->label(GameDay::transAttribute('day'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start')
                    ->label(GameDay::transAttribute('start'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('end')
                    ->label(GameDay::transAttribute('end'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make()
                    ->hideLabellnTooltip()
                    ->using(function (Model $record, array $data): Model {
                        try {
                            return app(UpdateGameDayAction::class)->execute(
                                $record,
                                GameDayData::create($data)
                            );
                        } catch (Throwable $th) {
                            Notification::make()
                                ->title('Es ist ein Fehler beim Bearbeiten des Datensetzes aufgetreten')
                                ->danger()
                                ->send();

                            throw $th;
                        }
                    }),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditGameDay::route('/{record}/test-edit'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'day';
    }
}
