<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

use DateTimeInterface;
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
     * @param DateTimeInterface|string $date Date in DD/MM/YYYY format
     */
    public function setNoticeDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_proidopoihshdate', $date);
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
