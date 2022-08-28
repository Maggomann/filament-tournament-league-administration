<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits;

trait HasHideableLabel
{
    protected ?string $originLabel = null;

    public function hideLabel(): static
    {
        if ($this->originLabel === null) {
            $this->originLabel = $this->label;
        }

        $this->label = '';

        return $this;
    }

    public function hideLabelAndShowAsTooltip(): static
    {
        $this->hideLabel();

        $this->tooltip($this->originLabel);

        return $this;
    }
}
