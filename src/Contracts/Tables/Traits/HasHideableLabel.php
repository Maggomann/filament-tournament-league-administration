<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Contracts\Tables\Traits;

trait HasHideableLabel
{
    public function hideLabel(bool $showHideLabelAsTooltip = true): static
    {
        if ($showHideLabelAsTooltip) {
            $label = $this->modelLabel ?? $this->label;

            $this->tooltip($label);
        }

        $this->label = '';
        $this->modelLabel = '';

        return $this;
    }
}
