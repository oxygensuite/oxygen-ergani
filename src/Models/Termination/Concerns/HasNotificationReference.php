<?php

namespace OxygenSuite\OxygenErgani\Models\Termination\Concerns;

use DateTime;

/**
 * Notification reference fields for E5AO (resignation after notification).
 *
 * Links the resignation to the previous E5O notification submission.
 */
trait HasNotificationReference
{
    /**
     * Get the notification (E5O) protocol number.
     */
    public function getNotificationProtocol(): ?string
    {
        return $this->get('f_oxlhsh_protocol');
    }

    /**
     * Set the notification (E5O) protocol number.
     *
     * @param string $protocol Protocol number from E5O submission (max 50 chars)
     */
    public function setNotificationProtocol(string $protocol): static
    {
        return $this->set('f_oxlhsh_protocol', $protocol);
    }

    /**
     * Get the notification (E5O) submission date.
     */
    public function getNotificationDate(): ?string
    {
        return $this->get('f_oxlhsh_date_ypovolis');
    }

    /**
     * Set the notification (E5O) submission date.
     *
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setNotificationDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_oxlhsh_date_ypovolis', $date);
    }
}
