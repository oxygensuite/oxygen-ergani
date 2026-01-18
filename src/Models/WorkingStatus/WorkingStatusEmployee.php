<?php

namespace OxygenSuite\OxygenErgani\Models\WorkingStatus;

use OxygenSuite\OxygenErgani\Factories\WorkingStatus\WorkingStatusEmployeeFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Working status employee model for WKChgWK schema.
 *
 * Contains individual employee working status information.
 *
 * @method static WorkingStatusEmployeeFactory factory(int $count = 1)
 */
class WorkingStatusEmployee extends Model
{
    use HasFactory;

    /** @var array<string, string> */
    protected array $casts = [
        'f_full_employment_hours' => 'greek_float:1',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm',
        'f_eponymo',
        'f_onoma',
        'f_date',
        'f_working_time_digital_organization',
        'f_full_employment_hours',
        'f_week_days',
        'f_euelikto_wrario_minutes',
        'f_working_card',
        'f_dialeimma_minutes',
        'f_dialeimma_entos_wrariou',
    ];

    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    public function setDate(string $date): static
    {
        return $this->set('f_date', $date);
    }

    public function getWorkingTimeDigitalOrganization(): ?string
    {
        return $this->get('f_working_time_digital_organization');
    }

    public function setWorkingTimeDigitalOrganization(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_working_time_digital_organization', $value);
    }

    public function getFullEmploymentHours(): ?float
    {
        return $this->greekFloat('f_full_employment_hours');
    }

    public function setFullEmploymentHours(float $hours): static
    {
        return $this->set('f_full_employment_hours', $hours);
    }

    public function getWeekDays(): ?string
    {
        return $this->get('f_week_days');
    }

    public function setWeekDays(string|int $days): static
    {
        return $this->set('f_week_days', (string) $days);
    }

    public function getFlexibleArrivalMinutes(): ?int
    {
        return $this->int('f_euelikto_wrario_minutes');
    }

    public function setFlexibleArrivalMinutes(int $minutes): static
    {
        return $this->set('f_euelikto_wrario_minutes', $minutes);
    }

    public function getWorkingCard(): ?string
    {
        return $this->get('f_working_card');
    }

    public function setWorkingCard(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_working_card', $value);
    }

    public function getBreakMinutes(): ?int
    {
        return $this->int('f_dialeimma_minutes');
    }

    public function setBreakMinutes(int $minutes): static
    {
        return $this->set('f_dialeimma_minutes', $minutes);
    }

    public function getBreakWithinSchedule(): ?string
    {
        return $this->get('f_dialeimma_entos_wrariou');
    }

    public function setBreakWithinSchedule(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_dialeimma_entos_wrariou', $value);
    }
}
