<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules;

use Illuminate\Contracts\Validation\Rule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class GameScheduleStartedAtRule extends ValidationRule implements Rule
{
    protected string $value;

    protected string $translationRuleKey = 'rules.started_at_must_be_smaller_than_ended_at';

    public function __construct(
        public ?string $ended_at,
        public ?GameSchedule $gameSchedule
    ) {
    }

    public function message(): string
    {
        $translationKey = static::$translateablePackageKey;
        $translationKey .= $this->translationRuleKey;

        return trans($translationKey, ['value' => $this->value]);
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        if (true === $this->gameSchedule?->days()
            ->where(function ($query) {
                return $query->where(function ($query) {
                    return $query->whereNotNull('ended_at')->where('ended_at', '<', $this->value);
                })->orWhere(function ($query) {
                    return $query->whereNotNull('started_at')->where('started_at', '<', $this->value);
                });
            })
            ->exists()
        ) {
            $this->translationRuleKey = 'rules.game_schedule_started_at_must_be_outside_from_the_days_time_periods';

            return false;
        }

        return $this->value < $this->ended_at;
    }
}
