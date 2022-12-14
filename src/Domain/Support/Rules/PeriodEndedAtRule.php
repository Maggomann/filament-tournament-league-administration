<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodEndedAtRule extends ValidationRule implements Rule
{
    protected string $value;

    public function __construct(public ?string $started_at)
    {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= 'rules.ended_at_must_be_greater_than_started_at';

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return $this->value > $this->started_at;
    }
}
