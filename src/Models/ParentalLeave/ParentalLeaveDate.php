<?php

namespace OxygenSuite\OxygenErgani\Models\ParentalLeave;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Leave date entry (DatesE14) of an E14 parental leave declaration.
 *
 * Either a full-day leave (`setFullDay()`) or an hourly leave within the
 * employee's schedule (`setHours()` + `setSchedule()`).
 *
 * @see xsd/E14_v1.xsd
 */
class ParentalLeaveDate extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_date_leave',
        'f_hour_from',
        'f_hour_to',
        'f_orario_from',
        'f_orario_to',
        'f_plires_orario',
    ];

    public function getDate(): ?string
    {
        return $this->get('f_date_leave');
    }

    public function setDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_leave', $date);
    }

    public function getHourFrom(): ?string
    {
        return $this->get('f_hour_from');
    }

    /**
     * @param string $time Leave start time (HH:MM)
     */
    public function setHourFrom(string $time): static
    {
        return $this->set('f_hour_from', $time);
    }

    public function getHourTo(): ?string
    {
        return $this->get('f_hour_to');
    }

    /**
     * @param string $time Leave end time (HH:MM)
     */
    public function setHourTo(string $time): static
    {
        return $this->set('f_hour_to', $time);
    }

    /**
     * Set the leave hours for an hourly leave.
     */
    public function setHours(string $from, string $to): static
    {
        return $this->setHourFrom($from)->setHourTo($to);
    }

    public function getScheduleFrom(): ?string
    {
        return $this->get('f_orario_from');
    }

    /**
     * @param string $time Start of the employee's working schedule that day (HH:MM)
     */
    public function setScheduleFrom(string $time): static
    {
        return $this->set('f_orario_from', $time);
    }

    public function getScheduleTo(): ?string
    {
        return $this->get('f_orario_to');
    }

    /**
     * @param string $time End of the employee's working schedule that day (HH:MM)
     */
    public function setScheduleTo(string $time): static
    {
        return $this->set('f_orario_to', $time);
    }

    /**
     * Set the employee's working schedule for the leave day.
     */
    public function setSchedule(string $from, string $to): static
    {
        return $this->setScheduleFrom($from)->setScheduleTo($to);
    }

    /**
     * Whether the leave covers the full working schedule of the day.
     */
    public function getFullDay(): ?string
    {
        return $this->get('f_plires_orario');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setFullDay(string|bool $value = true): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_plires_orario', $value);
    }
}
