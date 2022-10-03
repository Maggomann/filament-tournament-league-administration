<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class UniqueGameDayRule extends ValidationRule implements Rule
{
    protected int $value;

    public function __construct(public GameSchedule $gameSchedule)
    {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'rules.unique_game_day';

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return (bool) ($this->gameSchedule?->days()
            ?->where('day', $value)
            ?->doesntExist());
    }
}
