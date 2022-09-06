<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AdressesResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Address\Actions\CreateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Address\Actions\UpdateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Address\DTO\CreateAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Application\Address\DTO\UpdateAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\CreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Maggomann\LaravelAddressable\Models\AddressCategory;
use Maggomann\LaravelAddressable\Models\AddressGender;
use Throwable;

class AdressesRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Nur zum Testen für das Addressable-Paket, kommt später wieder weg
                Select::make('category_id')
                    ->label(__('laravel-addressable.attributes.addresses.category_id'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.category_id'))
                    ->options(
                        AddressCategory::all()->pluck('title_translation_key', 'id')
                            ->mapWithKeys(fn ($value, $key) => [$key => __("laravel-addressable.{$value}")])
                    )
                    ->required()
                    ->searchable(),

                Select::make('gender_id')
                    ->label(__('laravel-addressable.attributes.addresses.gender_id'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.gender_id'))
                    ->options(
                        AddressGender::all()->pluck('title_translation_key', 'id')
                            ->mapWithKeys(fn ($value, $key) => [$key => __("laravel-addressable.{$value}")])
                    )
                    ->required()
                    ->searchable(),

                TextInput::make('first_name')
                    ->label(__('laravel-addressable.attributes.addresses.first_name'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.first_name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->label(__('laravel-addressable.attributes.addresses.last_name'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.last_name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('street_address')
                    ->label(__('laravel-addressable.attributes.addresses.street_address'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.street_address'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('street_addition')
                    ->label(__('laravel-addressable.attributes.addresses.street_addition'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.street_addition'))
                    ->maxLength(255),

                // TODO: Validierung anpassen, gibt auch länder ohne postleitzahlen
                TextInput::make('postal_code')
                    ->label(__('laravel-addressable.attributes.addresses.postal_code'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.postal_code'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('city')
                    ->label(__('laravel-addressable.attributes.addresses.city'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.city'))
                    ->required()
                    ->maxLength(255),

                Select::make('country_code')
                    ->label(__('laravel-addressable.attributes.addresses.country_code'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.country_code'))
                    ->options(
                        collect(countries(true))
                            ->mapWithKeys(function ($country) {
                                $commonName = Arr::get($country, 'name.common');

                                $languages = collect(Arr::get($country, 'languages')) ?? collect();

                                $language = $languages->keys()->first() ?? null;

                                $nativeNames = Arr::get($country, 'name.native');

                                if (
                                    filled($language) &&
                                        filled($nativeNames) &&
                                        filled($nativeNames[$language]) ?? null
                                ) {
                                    $native = $nativeNames[$language]['common'] ?? null;
                                }

                                if (blank($native ?? null) && filled($nativeNames)) {
                                    $native = collect($nativeNames)->first()['common'] ?? null;
                                }

                                $native = $native ?? $commonName;

                                if ($native !== $commonName && filled($native)) {
                                    $native = "$native ($commonName)";
                                }

                                return [Arr::get($country, 'iso_3166_1_alpha2') => $native];
                            })
                    )
                    ->required()
                    ->searchable(),

                Toggle::make('is_main')
                    ->label(__('laravel-addressable.attributes.addresses.is_main'))
                    ->columnSpan(2),

                Toggle::make('is_preferred')
                    ->label(__('laravel-addressable.attributes.addresses.is_preferred'))
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street_address')
                    ->label(__('laravel-addressable.attributes.addresses.street_address')),
                TextColumn::make('postal_code')
                    ->label(__('laravel-addressable.attributes.addresses.postal_code')),
                TextColumn::make('city')
                    ->label(__('laravel-addressable.attributes.addresses.city')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (HasRelationshipTable $livewire, array $data): Model {
                        try {
                            return app(CreateAddressAction::class)->execute(
                                $livewire->getRelationship(),
                                CreateAddressData::create($data)
                            );
                        } catch (Throwable $th) {
                            Notification::make()
                                ->title('Es ist ein Fehler beim Erstellen des Datensetzes aufgetreten')
                                ->danger()
                                ->send();

                            throw $th;
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->hideLabellnTooltip()
                    ->using(function (Model $record, array $data): Model {
                        try {
                            return app(UpdateAddressAction::class)->execute(
                                $record,
                                UpdateAddressData::create($data)
                            );
                        } catch (Throwable $th) {
                            Notification::make()
                                ->title('Es ist ein Fehler beim Erstellen des Datensetzes aufgetreten')
                                ->danger()
                                ->send();

                            throw $th;
                        }
                    }),
                ViewAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
