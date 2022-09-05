<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AdressesResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\CreateAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\EditAction;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Actions\ViewAction;
use Maggomann\FilamentTournamentLeagueAdministration\Resources\TranslateableRelationManager;
use Maggomann\LaravelAddressable\Models\AddressCategory;
use Maggomann\LaravelAddressable\Models\AddressGender;

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
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make()->hideLabellnTooltip(),
                ViewAction::make()->hideLabellnTooltip(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
