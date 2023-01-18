<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Domain\Support\Tables\Traits;

use Closure;

trait HasIconLabel
{
    protected ?string $hiddenLabel = null;

    protected bool $ishiddenLabelDisplayedInTooltip = false;

    public function onlyIcon(): static
    {
        return $this->markHiddenLabelWithLabelIfNotYetDone()
            ->markLabelWithEmptyString();
    }

    public function onlyIconAndTooltip(): static
    {
        return $this->hiddenLabelIsDisplayedInTooltip()
            ->markHiddenLabelWithLabelIfNotYetDone()
            ->markTooltipWithHiddenLabel()
            ->markLabelWithEmptyString();
    }

    public function tooltip(string|Closure|null $tooltip): static
    {
        if ($this->ishiddenLabelDisplayedInTooltip) {
            return $this->onlyIconAndTooltip();
        }

        $this->tooltip = $tooltip;

        return $this;
    }

    private function hiddenLabelIsDisplayedInTooltip(): static
    {
        $this->ishiddenLabelDisplayedInTooltip = true;

        return $this;
    }

    private function markTooltipWithHiddenLabel(): static
    {
        $this->tooltip = $this->hiddenLabel;

        return $this;
    }

    private function markHiddenLabelWithLabelIfNotYetDone(): static
    {
        if (blank($this->hiddenLabel)) {
            $this->hiddenLabel = $this->label;
        }

        return $this;
    }

    private function markLabelWithEmptyString(): static
    {
        $this->label = '';

        return $this;
    }
}
