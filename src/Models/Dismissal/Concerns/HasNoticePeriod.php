<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\NoticePeriodMonths;

/**
 * Notice period fields for dismissals with advance notice.
 *
 * Used in: E6NMP only
 */
trait HasNoticePeriod
{
    /**
     * Get the date when advance notice was given.
     */
    public function getNoticeDate(): ?string
    {
        return $this->get('f_proidopoihshdate');
    }

    /**
     * Set the date when advance notice was given.
     *
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setNoticeDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_proidopoihshdate', $date);
    }

    /**
     * Get the notice period in months.
     */
    public function getNoticePeriodMonths(): ?int
    {
        $value = $this->get('f_minesproidopoihsh');

        return $value !== null ? (int) $value : null;
    }

    /**
     * Set the notice period in months.
     *
     * @param NoticePeriodMonths|int $months 1-4 months
     */
    public function setNoticePeriodMonths(NoticePeriodMonths|int $months): static
    {
        if ($months instanceof NoticePeriodMonths) {
            $months = $months->value;
        }

        return $this->set('f_minesproidopoihsh', (string) $months);
    }
}
