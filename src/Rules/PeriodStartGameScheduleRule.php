<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodStartGameScheduleRule extends ValidationRule implements Rule
{
    protected string $value;

    public function __construct(public ?string $ended_at)
    {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'rules.started_at_must_be_smaller_than_ended_at';

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return $this->value < $this->ended_at;
    }
}
