<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class EndedAtGameDayRule extends ValidationRule implements Rule
{
    protected string $value;

    protected string $translationRuleKey = 'rules.started_at_game_day';

    public function __construct(
        public GameSchedule $gameSchedule,
        public ?int $day,
        public ?string $startedAt
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

        if ($this->startedAt >= $this->value) {
            $this->translationRuleKey = 'rules.game_day_end_must_be_greater_than_start_date';

            return false;
        }

        if (true === $this->gameSchedule->days()
            ->where('day', '>', $this->day)
            ->where('started_at', '<=', $this->value)
            ->exists()
        ) {
            $this->translationRuleKey = 'rules.game_day_end_pre_days';

            return false;
        }

        if ($this->value <= $this->gameSchedule->started_at {
            $this->translationRuleKey = 'rules.game_day_started_at_must_be_between_game_schedule_dates';

            return false;
        }

        if ($this->value >= $this->gameSchedule->ended_at) {
            $this->translationRuleKey = 'rules.game_day_started_at_must_be_between_game_schedule_dates';

            return false;
        }

        return true;
    }
}
