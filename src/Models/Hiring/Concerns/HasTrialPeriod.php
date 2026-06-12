<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

use DateTime;

/**
 * Trial period fields shared by E3N, E3M, and E3PD schemas.
 *
 * Provides methods for trial period flag and end date.
 */
trait HasTrialPeriod
{
    /**
     * Whether employment includes a trial period.
     */
    public function getTrialPeriod(): ?string
    {
        return $this->get('f_trial_period');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setTrialPeriod(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_trial_period', $value);
    }

    /**
     * Get the trial period end date.
     */
    public function getTrialPeriodEndDate(): ?string
    {
        return $this->get('f_trial_date_to');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setTrialPeriodEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_trial_date_to', $date);
    }
}
