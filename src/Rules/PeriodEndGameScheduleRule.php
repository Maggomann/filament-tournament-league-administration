<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodEndGameScheduleRule extends ValidationRule implements Rule
{
    protected string $value;

    public function __construct(public ?string $period_start)
    {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'rules.period_end_game_schedule_must_be_greather_than_period_start';

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return $this->value > $this->period_start;
    }
}
