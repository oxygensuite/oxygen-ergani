<?php

namespace OxygenSuite\OxygenErgani\Models\Termination\Concerns;

/**
 * Compensation amount for termination forms with severance pay.
 *
 * Used in: E5E (voluntary exit with compensation), E5S (voluntary retirement),
 * E5DS (mandatory retirement after 15 years/age limit)
 */
trait HasCompensation
{
    /**
     * Get the compensation/severance amount.
     */
    public function getCompensationAmount(): ?float
    {
        return $this->greekFloat('f_posoapozimiosis');
    }

    /**
     * Set the compensation/severance amount.
     *
     * @param float $amount Compensation amount (e.g., 5000.00)
     */
    public function setCompensationAmount(float $amount): static
    {
        return $this->set('f_posoapozimiosis', $amount);
    }
}
