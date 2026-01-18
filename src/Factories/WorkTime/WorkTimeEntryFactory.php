<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating WorkTimeEntry instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry>
 */
class WorkTimeEntryFactory extends Factory
{
    /**
     * Define the default attribute values for WorkTimeEntry.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_type' => WorkTimeType::WORK->value,
            'f_from' => '09:00',
            'f_to' => '17:00',
            'f_year' => '',
            'f_req_days' => '',
        ];
    }

    /**
     * Configure as regular work entry.
     */
    public function work(string $from = '09:00', string $to = '17:00'): static
    {
        return $this->state([
            'f_type' => WorkTimeType::WORK->value,
            'f_from' => $from,
            'f_to' => $to,
        ]);
    }

    /**
     * Configure as overtime entry.
     */
    public function overtime(string $from = '17:00', string $to = '19:00'): static
    {
        return $this->state([
            'f_type' => WorkTimeType::OVERTIME->value,
            'f_from' => $from,
            'f_to' => $to,
        ]);
    }

    /**
     * Configure as day off.
     */
    public function dayOff(): static
    {
        return $this->state([
            'f_type' => WorkTimeType::DAY_OFF->value,
            'f_from' => '',
            'f_to' => '',
        ]);
    }

    /**
     * Configure as regular leave.
     */
    public function leaveRegular(string $year, string $requestedDays): static
    {
        return $this->state([
            'f_type' => WorkTimeType::LEAVE_REGULAR->value,
            'f_from' => '',
            'f_to' => '',
            'f_year' => $year,
            'f_req_days' => $requestedDays,
        ]);
    }

    /**
     * Configure with a specific work time type.
     */
    public function withType(WorkTimeType|string $type): static
    {
        if ($type instanceof WorkTimeType) {
            $type = $type->value;
        }

        return $this->state([
            'f_type' => $type,
        ]);
    }

    /**
     * Configure with specific time range.
     */
    public function withTimeRange(string $from, string $to): static
    {
        return $this->state([
            'f_from' => $from,
            'f_to' => $to,
        ]);
    }

    /**
     * Configure for leave with year and days.
     */
    public function forLeave(string $year, string $requestedDays): static
    {
        return $this->state([
            'f_from' => '',
            'f_to' => '',
            'f_year' => $year,
            'f_req_days' => $requestedDays,
        ]);
    }

    /**
     * Configure for morning shift (06:00 - 14:00).
     */
    public function morningShift(): static
    {
        return $this->work('06:00', '14:00');
    }

    /**
     * Configure for afternoon shift (14:00 - 22:00).
     */
    public function afternoonShift(): static
    {
        return $this->work('14:00', '22:00');
    }

    /**
     * Configure for night shift (22:00 - 06:00).
     */
    public function nightShift(): static
    {
        return $this->work('22:00', '06:00');
    }
}
