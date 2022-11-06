<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateOrCreateEventLocationAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\EventLocationAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DeleteEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\CreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\DeleteAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\FreeTournament;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions\CountryCodeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions\EventLocationSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Maggomann\LaravelAddressable\Models\Address;
use Maggomann\LaravelAddressable\Models\AddressGender;
use Throwable;

class EventLocalctionAddressRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'address';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.event_location', number: 1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Nur zum Testen für das Addressable-Paket, kommt später wieder weg

                Select::make('event_location_id')
                    ->label(FreeTournament::transAttribute('event_location'))
                    ->validationAttribute(FreeTournament::transAttribute('event_location'))
                    ->options(fn () => EventLocationSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'event_location')
                    )
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(
                        function (Closure $set, Closure $get) {
                            $address = Address::find($get('event_location_id'));

                            if ($address) {
                                $set('company', $address->company);
                                $set('gender_id', $address->gender_id);
                                $set('first_name', $address->first_name);
                                $set('last_name', $address->last_name);
                                $set('street_address', $address->street_address);
                                $set('street_addition', $address->street_addition);
                                $set('postal_code', $address->postal_code);
                                $set('city', $address->city);
                                $set('country_code', $address->country_code);
                            }
                        })
                    ->columnSpan(2),

                TextInput::make('company')
                    ->label(__('laravel-addressable.attributes.addresses.company'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.company'))
                    ->maxLength(255),

                Select::make('gender_id')
                    ->label(__('laravel-addressable.attributes.addresses.gender_id'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.gender_id'))
                    ->options(
                        AddressGender::all()->pluck('title_translation_key', 'id')
                            ->mapWithKeys(fn ($value, $key) => [$key => __("laravel-addressable.{$value}")])
                    )
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_gender_id')
                    )
                    ->default(1)
                    ->searchable(),

                TextInput::make('first_name')
                    ->label(__('laravel-addressable.attributes.addresses.first_name'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.first_name'))
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->label(__('laravel-addressable.attributes.addresses.last_name'))
                    ->validationAttribute(__('laravel-addressable.attributes.addresses.last_name'))
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
                    ->options(fn () => CountryCodeSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_country_id')
                    )
                    ->default('DE')
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company')
                    ->label(__('laravel-addressable.attributes.addresses.company')),
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
                    ->using(function (HasRelationshipTable $livewire, array $data): Address {
                        try {
                            return app(UpdateOrCreateEventLocationAddressAction::class)->execute(
                                $livewire->getRelationship()->getParent(),
                                EventLocationAddressData::create($data),
                                $livewire->getRelationship()->getParent()->address
                            );
                        } catch (Throwable) {
                            DeleteEntryFailedNotification::make()->send();
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->hideLabellnTooltip()
                    ->using(function (HasRelationshipTable $livewire, Model $record, array $data): Address {
                        try {
                            return app(UpdateOrCreateEventLocationAddressAction::class)->execute(
                                $livewire->getRelationship()->getParent(),
                                EventLocationAddressData::create($data),
                                $record
                            );
                        } catch (Throwable) {
                            EditEntryFailedNotification::make()->send();
                        }
                    }),
                ViewAction::make()->hideLabellnTooltip(),
                DeleteAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
