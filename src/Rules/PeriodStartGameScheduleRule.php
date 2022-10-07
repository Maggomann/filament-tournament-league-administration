<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodStartGameScheduleRule extends ValidationRule implements Rule
{
    protected string $value;

    public function __construct(public ?string $period_end)
    {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'rules.started_at_game_schedule_must_be_smaller_than_period_end';

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return $this->value < $this->period_end;
    }
}
