<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Rules;

use Illuminate\Contracts\Validation\Rule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameSchedule;

class EndedAtGameDayRule extends ValidationRule implements Rule
{
    protected string $value;

    protected string $translationRuleKey = 'rules.ended_at_must_be_greater_than_started_at';

    public function __construct(
        public GameSchedule $gameSchedule,
        public ?int $day,
        public ?string $startedAt,
        public ?GameDay $gameDay
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
            $this->translationRuleKey = 'rules.ended_at_must_be_greater_than_started_at';

            return false;
        }

        if (true === $this->gameSchedule->days()
            ->where('id', '!=', $this->gameDay?->id)
            ->where('day', '>', $this->day)
            ->where('started_at', '<=', $this->value)
            ->exists()
        ) {
            $this->translationRuleKey = 'rules.game_day_ended_at_pre_days';

            return false;
        }

        if ($this->value <= $this->gameSchedule->started_at) {
            $this->translationRuleKey = 'rules.game_day_date_must_be_between_game_schedule_dates';

            return false;
        }

        if ($this->value >= $this->gameSchedule->ended_at) {
            $this->translationRuleKey = 'rules.game_day_date_must_be_between_game_schedule_dates';

            return false;
        }

        return true;
    }
}
