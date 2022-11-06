<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Resources\AddressesResource\SelectOptions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CountryCodeSelect
{
    public static function options(): Collection
    {
        return collect(countries(true))
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
            });
    }
}
