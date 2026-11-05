<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

use DateTime;

/**
 * Fixed-term period fields (`f_orismenou_apo` / `f_orismenou_ews`) shared by E3.1 and E3.3.
 */
trait HasFixedTermPeriod
{
    public function getStartDate(): ?string
    {
        return $this->get('f_orismenou_apo');
    }

    /**
     * Fixed-term period start date.
     */
    public function setStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_apo', $date);
    }

    public function getEndDate(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    /**
     * Fixed-term period end date.
     */
    public function setEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_ews', $date);
    }

    /**
     * Set the fixed-term period in one call.
     */
    public function setFixedTermPeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setStartDate($from)->setEndDate($to);
    }
}
