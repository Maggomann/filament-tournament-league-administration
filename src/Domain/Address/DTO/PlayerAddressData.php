<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\DTO;

use Maggomann\FilamentTournamentLeagueAdministration\Domain\Address\Contracts\AddressData;

class PlayerAddressData extends AddressData
{
    public function __construct(
        public int $category_id,
        public int $gender_id,
        public string $first_name,
        public string $last_name,
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
}
