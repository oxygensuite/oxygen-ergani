<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Enums\DayOfWeek;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating WorkTimeEmployee instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee>
 */
class WorkTimeEmployeeFactory extends Factory
{
    /**
     * Define the default attribute values for WorkTimeEmployee.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = self::fake()->dateTimeBetween('-1 week', 'now');

        return [
            'f_afm' => self::fake()->afm(),
            'f_eponymo' => self::fake()->greekLastName(),
            'f_onoma' => self::fake()->greekFirstName(),
            'f_date' => $date->format('d/m/Y'),
            'f_day' => '',
            'ErgazomenosAnalytics' => fn() => ['ErgazomenosWTOAnalytics' => [WorkTimeEntryFactory::new()->make()]],
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
     * Configure for a specific date (daily submissions).
     */
    public function forDate(string $date): static
    {
        return $this->state([
            'f_date' => $date,
            'f_day' => '',
        ]);
    }

    /**
     * Configure for a specific day of week (weekly submissions).
     */
    public function forDay(DayOfWeek|string $day): static
    {
        if ($day instanceof DayOfWeek) {
            $day = $day->value;
        }

        return $this->state([
            'f_day' => $day,
            'f_date' => '',
        ]);
    }

    /**
     * Configure for Monday (weekly submissions).
     */
    public function onMonday(): static
    {
        return $this->forDay(DayOfWeek::MONDAY);
    }

    /**
     * Configure for Tuesday (weekly submissions).
     */
    public function onTuesday(): static
    {
        return $this->forDay(DayOfWeek::TUESDAY);
    }

    /**
     * Configure for Wednesday (weekly submissions).
     */
    public function onWednesday(): static
    {
        return $this->forDay(DayOfWeek::WEDNESDAY);
    }

    /**
     * Configure for Thursday (weekly submissions).
     */
    public function onThursday(): static
    {
        return $this->forDay(DayOfWeek::THURSDAY);
    }

    /**
     * Configure for Friday (weekly submissions).
     */
    public function onFriday(): static
    {
        return $this->forDay(DayOfWeek::FRIDAY);
    }

    /**
     * Configure for Saturday (weekly submissions).
     */
    public function onSaturday(): static
    {
        return $this->forDay(DayOfWeek::SATURDAY);
    }

    /**
     * Configure for Sunday (weekly submissions).
     */
    public function onSunday(): static
    {
        return $this->forDay(DayOfWeek::SUNDAY);
    }

    /**
     * Configure with multiple time entries.
     *
     * @param int $count Number of entries to generate
     */
    public function withEntries(int $count = 1): static
    {
        return $this->state([
            'ErgazomenosAnalytics' => fn() => ['ErgazomenosWTOAnalytics' => WorkTimeEntryFactory::new()->count($count)->make()],
        ]);
    }

    /**
     * Configure without any time entries.
     */
    public function withoutEntries(): static
    {
        return $this->state([
            'ErgazomenosAnalytics' => ['ErgazomenosWTOAnalytics' => []],
        ]);
    }

    /**
     * Configure with specific time entries.
     *
     * @param array<int, \OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry> $entries
     */
    public function withSpecificEntries(array $entries): static
    {
        return $this->state([
            'ErgazomenosAnalytics' => ['ErgazomenosWTOAnalytics' => $entries],
        ]);
    }
}
