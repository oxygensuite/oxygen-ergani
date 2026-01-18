<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkingStatus;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating WorkingStatusEmployee instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatusEmployee>
 */
class WorkingStatusEmployeeFactory extends Factory
{
    /**
     * Define the default attribute values for WorkingStatusEmployee.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();

        return [
            'f_afm' => $fake->afm(),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_date' => (new DateTimeImmutable('today'))->format('d/m/Y'),
            'f_working_time_digital_organization' => '1',
            'f_full_employment_hours' => 40.0,
            'f_week_days' => '5',
            'f_euelikto_wrario_minutes' => '30',
            'f_working_card' => '1',
            'f_dialeimma_minutes' => '30',
            'f_dialeimma_entos_wrariou' => '1',
        ];
    }

    /**
     * Configure for a specific employee by TIN.
     */
    public function withTin(string $tin): static
    {
        return $this->state([
            'f_afm' => $tin,
        ]);
    }

    /**
     * Configure with employee name.
     */
    public function withName(string $firstName, string $lastName): static
    {
        return $this->state([
            'f_onoma' => $firstName,
            'f_eponymo' => $lastName,
        ]);
    }

    /**
     * Configure for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state([
            'f_date' => $date,
        ]);
    }

    /**
     * Configure for a standard 5-day work week.
     */
    public function fiveDayWeek(): static
    {
        return $this->state([
            'f_week_days' => '5',
        ]);
    }

    /**
     * Configure for a 6-day work week.
     */
    public function sixDayWeek(): static
    {
        return $this->state([
            'f_week_days' => '6',
        ]);
    }

    /**
     * Configure digital work time organization.
     */
    public function withDigitalOrganization(bool $enabled = true): static
    {
        return $this->state([
            'f_working_time_digital_organization' => $enabled ? '1' : '0',
        ]);
    }

    /**
     * Configure working card requirement.
     */
    public function withWorkingCard(bool $enabled = true): static
    {
        return $this->state([
            'f_working_card' => $enabled ? '1' : '0',
        ]);
    }

    /**
     * Configure full employment hours.
     */
    public function withFullEmploymentHours(float $hours): static
    {
        return $this->state([
            'f_full_employment_hours' => $hours,
        ]);
    }

    /**
     * Configure flexible arrival time.
     */
    public function withFlexibleArrival(int $minutes): static
    {
        return $this->state([
            'f_euelikto_wrario_minutes' => (string) $minutes,
        ]);
    }

    /**
     * Configure break settings.
     */
    public function withBreak(int $minutes, bool $withinSchedule = true): static
    {
        return $this->state([
            'f_dialeimma_minutes' => (string) $minutes,
            'f_dialeimma_entos_wrariou' => $withinSchedule ? '1' : '0',
        ]);
    }

    /**
     * Configure for part-time employee.
     */
    public function partTime(float $hours = 20.0): static
    {
        return $this->state([
            'f_full_employment_hours' => $hours,
        ]);
    }
}
