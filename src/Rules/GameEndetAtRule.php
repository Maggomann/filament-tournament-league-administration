<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Rules;

use Illuminate\Contracts\Validation\Rule;
use Maggomann\FilamentTournamentLeagueAdministration\Models\GameDay;

class GameEndetAtRule extends ValidationRule implements Rule
{
    protected string $value;

    protected string $translationRuleKey = 'rules.ended_at_must_be_greater_than_started_at';

    public function __construct(
        public ?string $startedAt,
        public ?int $gameDayId
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

        $gameDay = GameDay::find($this->gameDayId);

        if ($this->value <= $gameDay->started_at) {
            $this->translationRuleKey = 'rules.game_date_must_be_between_game_schedule_dates';

            return false;
        }

        if ($this->value >= $gameDay->ended_at) {
            $this->translationRuleKey = 'rules.game_date_must_be_between_game_schedule_dates';

            return false;
        }

        return $this->value > $this->startedAt;
    }
}
