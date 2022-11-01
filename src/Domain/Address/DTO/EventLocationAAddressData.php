<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO;

use Illuminate\Support\Arr;
use Maggomann\LaravelAddressable\Models\AddressCategory;
use Spatie\DataTransferObject\DataTransferObject;

class EventLocationAAddressData extends DataTransferObject
{
    public int $category_id;

    public ?int $gender_id;

    public ?string $first_name;

    public ?string $last_name;

    public ?string $name;

    public string $street_address;

    public ?string $street_addition;

    public ?string $postal_code;

    public string $city;

    public string $country_code;

    public ?string $state;

    public ?string $company;

    public ?string $job_title;

    public ?string $latitude;

    public ?string $longitude;

    public bool $is_preferred;

    public bool $is_main;

    /**
     * @param  array<mixed>  $args
     */
    public static function create(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        if (Arr::has($args, 'is_preferred') === false) {
            Arr::set($args, 'is_preferred', 1);
            Arr::set($args, 'is_main', 1);
            Arr::set($args, 'category_id', AddressCategory::firstWhere('title_translation_key', 'address_categories.title.standard')->id);
        }

        return new self($args);
    }
}
