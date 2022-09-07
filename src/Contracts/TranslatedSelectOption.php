<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts;

class TranslatedSelectOption
{
    public static function placeholder(string $translationKey): string
    {
        return ($translation = trans($translationKey)) !== $translationKey
        ? $translation
        : __('forms::components.select.placeholder');
    }
}
