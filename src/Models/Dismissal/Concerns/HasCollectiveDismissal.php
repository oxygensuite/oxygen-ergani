<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

/**
 * Collective dismissal fields for employer-initiated terminations.
 *
 * Used in: E6NXP, E6NMP only (NOT E6SXP - retirement dismissals are individual)
 */
trait HasCollectiveDismissal
{
    /**
     * Whether this dismissal is part of a collective layoff.
     */
    public function isCollectiveDismissal(): bool
    {
        return $this->get('f_omadiki') === '1';
    }

    /**
     * @param bool $isCollective Whether dismissal is part of collective layoff
     */
    public function setCollectiveDismissal(bool $isCollective): static
    {
        return $this->set('f_omadiki', $isCollective ? '1' : '0');
    }

    /**
     * Get the collective dismissal decision number.
     */
    public function getCollectiveDismissalNumber(): ?string
    {
        return $this->get('f_omadikiarithmos');
    }

    /**
     * @param string|null $number Decision number (max 20 chars)
     */
    public function setCollectiveDismissalNumber(?string $number): static
    {
        return $this->set('f_omadikiarithmos', $number ?? '');
    }

    /**
     * Get the collective dismissal decision date.
     */
    public function getCollectiveDismissalDate(): ?string
    {
        return $this->get('f_omadikidate');
    }

    /**
     * @param string|null $date Date in DD/MM/YYYY format
     */
    public function setCollectiveDismissalDate(?string $date): static
    {
        return $this->set('f_omadikidate', $date ?? '');
    }
}
