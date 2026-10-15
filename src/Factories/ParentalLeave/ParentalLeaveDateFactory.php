<?php

namespace OxygenSuite\OxygenErgani\Factories\ParentalLeave;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ParentalLeaveDate instances with fake data.
 *
 * Creates full-day leave dates by default.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDate>
 */
class ParentalLeaveDateFactory extends Factory
{
    /**
     * Define the default attribute values for ParentalLeaveDate.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_date_leave' => self::fake()->greekDate('now', '+1 month'),
            'f_plires_orario' => '1',
        ];
    }

    /**
     * Configure for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state([
            'f_date_leave' => $date,
        ]);
    }

    /**
     * Configure as an hourly leave within the employee's schedule.
     */
    public function hourly(string $from = '12:00', string $to = '14:00', string $scheduleFrom = '09:00', string $scheduleTo = '17:00'): static
    {
        return $this->state([
            'f_hour_from' => $from,
            'f_hour_to' => $to,
            'f_orario_from' => $scheduleFrom,
            'f_orario_to' => $scheduleTo,
            'f_plires_orario' => '0',
        ]);
    }
}
