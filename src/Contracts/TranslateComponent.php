<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts;

class TranslateComponent
{
    public static function placeholder(string $translateablePackageKey, string $translationKey): string
    {
        $translationKey = "{$translateablePackageKey}translations.forms.components.select.placeholder.{$translationKey}";

        return ($translation = trans($translationKey)) !== $translationKey
        ? $translation
        : __('forms::components.select.placeholder');
    }

    public static function tab(string $translateablePackageKey, string $translationKey): string
    {
        $translationKey = "{$translateablePackageKey}translations.forms.components.tabs.{$translationKey}";

        return  __($translationKey);
    }
}
