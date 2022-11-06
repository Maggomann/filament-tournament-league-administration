<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Actions\UpdateOrCreateAddressAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO\PlayerAddressData;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DeleteEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\CreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions\CountryCodeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Maggomann\LaravelAddressable\Models\Address;
use Maggomann\LaravelAddressable\Models\AddressCategory;
use Maggomann\LaravelAddressable\Models\AddressGender;
use Throwable;

class AddressesRelationManager extends TranslateableRelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return static::$title ?? trans_choice(static::$translateablePackageKey.'filament-model.models.address', number: 2);
    }

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
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_category_id')
                    )
                    ->default(1)
                    ->required()
                    ->searchable(),

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
                    ->options(fn () => CountryCodeSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_country_id')
                    )
                    ->default('DE')
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
                    ->using(function (HasRelationshipTable $livewire, array $data): Address {
                        try {
                            return app(UpdateOrCreateAddressAction::class)->execute(
                                $livewire->getRelationship()->getParent(),
                                PlayerAddressData::create($data)
                            );
                        } catch (Throwable $e) {
                            dd($e);
                            DeleteEntryFailedNotification::make()->send();
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->hideLabellnTooltip()
                    ->using(function (HasRelationshipTable $livewire, Model $record, array $data): Address {
                        try {
                            return app(UpdateOrCreateAddressAction::class)->execute(
                                $livewire->getRelationship()->getParent(),
                                PlayerAddressData::create($data),
                                $record,
                            );
                        } catch (Throwable) {
                            EditEntryFailedNotification::make()->send();
                        }
                    }),
                ViewAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
