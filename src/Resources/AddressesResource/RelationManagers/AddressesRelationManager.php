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
use Maggomann\Addressable\Domain\Actions\UpdateOrCreateAddressAction;
use Maggomann\Addressable\Domain\DTO\AddressData;
use Maggomann\Addressable\Models\Address;
use Maggomann\Addressable\Models\AddressCategory;
use Maggomann\Addressable\Models\AddressGender;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\CreateAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\EditAction;
use Maggomann\FilamentOnlyIconDisplay\Domain\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\DeleteEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Notifications\EditEntryFailedNotification;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\TranslateComponent;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions\CountryCodeSelect;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
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
                Select::make('category_id')
                    ->label(__('addressable.attributes.addresses.category_id'))
                    ->validationAttribute(__('addressable.attributes.addresses.category_id'))
                    ->options(
                        AddressCategory::all()->pluck('title_translation_key', 'id')
                            ->mapWithKeys(fn ($value, $key) => [$key => __("addressable.{$value}")])
                    )
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_category_id')
                    )
                    ->default(1)
                    ->required()
                    ->searchable(),

                Select::make('gender_id')
                    ->label(__('addressable.attributes.addresses.gender_id'))
                    ->validationAttribute(__('addressable.attributes.addresses.gender_id'))
                    ->options(
                        AddressGender::all()->pluck('title_translation_key', 'id')
                            ->mapWithKeys(fn ($value, $key) => [$key => __("addressable.{$value}")])
                    )
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_gender_id')
                    )
                    ->default(1)
                    ->required()
                    ->searchable(),

                TextInput::make('first_name')
                    ->label(__('addressable.attributes.addresses.first_name'))
                    ->validationAttribute(__('addressable.attributes.addresses.first_name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->label(__('addressable.attributes.addresses.last_name'))
                    ->validationAttribute(__('addressable.attributes.addresses.last_name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('street_address')
                    ->label(__('addressable.attributes.addresses.street_address'))
                    ->validationAttribute(__('addressable.attributes.addresses.street_address'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('street_addition')
                    ->label(__('addressable.attributes.addresses.street_addition'))
                    ->validationAttribute(__('addressable.attributes.addresses.street_addition'))
                    ->maxLength(255),

                // TODO: Adapt validation, there are also countries without postal codes
                TextInput::make('postal_code')
                    ->label(__('addressable.attributes.addresses.postal_code'))
                    ->validationAttribute(__('addressable.attributes.addresses.postal_code'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('city')
                    ->label(__('addressable.attributes.addresses.city'))
                    ->validationAttribute(__('addressable.attributes.addresses.city'))
                    ->required()
                    ->maxLength(255),

                Select::make('country_code')
                    ->label(__('addressable.attributes.addresses.country_code'))
                    ->validationAttribute(__('addressable.attributes.addresses.country_code'))
                    ->options(fn () => CountryCodeSelect::options())
                    ->placeholder(
                        TranslateComponent::placeholder(static::$translateablePackageKey, 'address_country_id')
                    )
                    ->default('DE')
                    ->required()
                    ->searchable(),

                Toggle::make('is_main')
                    ->label(__('addressable.attributes.addresses.is_main'))
                    ->columnSpan(2),

                Toggle::make('is_preferred')
                    ->label(__('addressable.attributes.addresses.is_preferred'))
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street_address')
                    ->label(__('addressable.attributes.addresses.street_address')),
                TextColumn::make('postal_code')
                    ->label(__('addressable.attributes.addresses.postal_code')),
                TextColumn::make('city')
                    ->label(__('addressable.attributes.addresses.city')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (HasRelationshipTable $livewire, array $data) {
                        try {
                            /** @var Player $player */
                            $player = $livewire->getRelationship()->getParent();

                            return app(UpdateOrCreateAddressAction::class)->execute(
                                $player,
                                AddressData::from($data)
                            );
                        } catch (Throwable) {
                            DeleteEntryFailedNotification::make()->send();
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->onlyIconAndTooltip()
                    ->using(function (HasRelationshipTable $livewire, Model $record, array $data) {
                        try {
                            /** @var Player $player */
                            $player = $livewire->getRelationship()->getParent();
                            /** @var Address $address */
                            $address = $record;

                            return app(UpdateOrCreateAddressAction::class)->execute(
                                $player,
                                AddressData::from($data),
                                $address,
                            );
                        } catch (Throwable) {
                            EditEntryFailedNotification::make()->send();
                        }
                    }),
                ViewAction::make()->onlyIconAndTooltip(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
