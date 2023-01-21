<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO;

use Illuminate\Support\Arr;
use Maggomann\LaravelAddressable\Domain\DTO\AddressData;
use Maggomann\LaravelAddressable\Models\AddressCategory;

class EventLocationAddressData extends AddressData
{
    public function __construct(
        public int $category_id,
        public null|int $gender_id,
        public null|string $first_name,
        public null|string $last_name,
        public null|string $name,
        public string $street_address,
        public null|string $street_addition,
        public null|string $postal_code,
        public string $city,
        public string $country_code,
        public null|string $state,
        public null|string $company,
        public null|string $job_title,
        public null|string $latitude,
        public null|string $longitude,
        public bool $is_preferred,
        public bool $is_main
    ) {
    }

    /**
     * @param  array<mixed>  $args
     */
    public static function create(...$args): static
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        if (Arr::has($args, 'is_preferred') === false) {
            Arr::set($args, 'is_preferred', 1);
            Arr::set($args, 'is_main', 1);
            Arr::set($args, 'category_id', AddressCategory::firstWhere('title_translation_key', 'address_categories.title.standard')->id);
        }

        return static::from($args);
    }
}
