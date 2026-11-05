<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

use DateTime;

/**
 * First-day placement and free-text schedule fields shared by E3.1, E3.3 and E3.4.
 */
trait HasPlacement
{
    public function getPlacementDate(): ?string
    {
        return $this->get('f_date_proslipsis');
    }

    /**
     * First working / training day.
     */
    public function setPlacementDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_proslipsis', $date);
    }

    public function getPlacementTime(): ?string
    {
        return $this->get('f_date_time_proslipsis');
    }

    /**
     * Start time on the first day (HH:MM).
     */
    public function setPlacementTime(string $value): static
    {
        return $this->set('f_date_time_proslipsis', $value);
    }

    public function getWeeklyHours(): ?string
    {
        return $this->get('f_week_hours');
    }

    /**
     * Weekly hours as free text (e.g. "40" or "40,0").
     */
    public function setWeeklyHours(int|string $value): static
    {
        return $this->set('f_week_hours', (string) $value);
    }

    public function getScheduleAttached(): ?string
    {
        return $this->get('f_wres_external');
    }

    /**
     * Whether the working / training days and hours are given in an attached file.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setScheduleAttached(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_wres_external', $value);
    }
}
