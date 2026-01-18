<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

/**
 * Termination notification date for immediate dismissals.
 *
 * Used in: E6NXP, E6SXP (forms with immediate termination without notice period)
 * NOT used in: E6NMP (uses HasNoticePeriod instead)
 */
trait HasTerminationNotification
{
    /**
     * Get the date when termination was notified to the employee.
     */
    public function getTerminationNotificationDate(): ?string
    {
        return $this->get('f_koinopoihshdate');
    }

    /**
     * Set the date when termination was notified to the employee.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setTerminationNotificationDate(string $date): static
    {
        return $this->set('f_koinopoihshdate', $date);
    }
}
