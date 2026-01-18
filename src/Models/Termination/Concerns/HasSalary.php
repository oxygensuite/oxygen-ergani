<?php

namespace OxygenSuite\OxygenErgani\Models\Termination\Concerns;

/**
 * Salary at departure shared by most E5 termination forms.
 *
 * Used in: E5N, E5AO, E5D, E5E, E5S, E5DS
 * NOT used in: E5O (notification only)
 */
trait HasSalary
{
    /**
     * Get the total gross salary at departure.
     */
    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    /**
     * Set the total gross salary at departure (monthly salary or daily wage).
     *
     * @param float $amount Salary amount (e.g., 1500.00)
     */
    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }
}
