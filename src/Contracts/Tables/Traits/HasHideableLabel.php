<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits;

use Closure;

trait HasHideableLabel
{
    protected ?string $hiddenLabel = null;

    protected bool $hiddenLabelDisplayedInTooltip = false;

    public function hideLabel(): static
    {
        return $this->markHiddenLabelWithLabelIfNotYetDone()
            ->markLabelWithEmptyString();
    }

    public function hideLabellnTooltip(): static
    {
        return $this->markHiddenLabelDisplayedInTooltip()
            ->hideLabel()
            ->markTooltipWithHiddenLabel();
    }

    public function tooltip(string | Closure | null $tooltip): static
    {
        if ($this->isHiddenLabelDisplayedInTooltip()) {
            return $this->hideLabellnTooltip();
        }

        $this->tooltip = $tooltip;

        return $this;
    }

    private function markHiddenLabelDisplayedInTooltip(): static
    {
        $this->hiddenLabelDisplayedInTooltip = true;

        return $this;
    }

    private function markTooltipWithHiddenLabel(): static
    {
        $this->tooltip = $this->originLabel;

        return $this;
    }

    private function markHiddenLabelWithLabelIfNotYetDone(): static
    {
        if ($this->hiddenLabel === null) {
            $this->hiddenLabel = $this->label;
        }

        return $this;
    }

    private function markLabelWithEmptyString(): static
    {
        $this->label = '';

        return $this;
    }

    public function isHiddenLabelDisplayedInTooltip(): bool
    {
        return $this->hiddenLabelDisplayedInTooltip;
    }
}
