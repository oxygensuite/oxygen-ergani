<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating WorkTime instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime>
 */
class WorkTimeFactory extends Factory
{
    /**
     * Define the default attribute values for WorkTime.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromDate = fake()->dateTimeBetween('-1 week', 'now');
        $toDate = clone $fromDate;

        return [
            'f_aa_pararthmatos' => (string) fake()->numberBetween(0, 99),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_comments' => '',
            'f_from_date' => $fromDate->format('d/m/Y'),
            'f_to_date' => $toDate->format('d/m/Y'),
            'Ergazomenoi' => fn() => ['ErgazomenoiWTO' => [WorkTimeEmployeeFactory::new()->make()]],
        ];
    }

    /**
     * Configure for a specific branch.
     */
    public function forBranch(int|string $branchCode): static
    {
        return $this->state([
            'f_aa_pararthmatos' => (string) $branchCode,
        ]);
    }

    /**
     * Configure for the main branch (code 0).
     */
    public function mainBranch(): static
    {
        return $this->forBranch(0);
    }

    /**
     * Configure with specific comments.
     */
    public function withComments(string $comments): static
    {
        return $this->state([
            'f_comments' => $comments,
        ]);
    }

    /**
     * Configure with a date range (for weekly submissions).
     */
    public function forDateRange(string $fromDate, string $toDate): static
    {
        return $this->state([
            'f_from_date' => $fromDate,
            'f_to_date' => $toDate,
        ]);
    }

    /**
     * Configure for a single date.
     */
    public function forDate(string $date): static
    {
        return $this->state([
            'f_from_date' => $date,
            'f_to_date' => $date,
        ]);
    }

    /**
     * Configure as a correction to a previous submission.
     */
    public function asCorrection(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure with multiple employees.
     *
     * @param int $count Number of employees to generate
     */
    public function withEmployees(int $count = 1): static
    {
        return $this->state([
            'Ergazomenoi' => fn() => ['ErgazomenoiWTO' => WorkTimeEmployeeFactory::new()->count($count)->make()],
        ]);
    }

    /**
     * Configure without any employees.
     */
    public function withoutEmployees(): static
    {
        return $this->state([
            'Ergazomenoi' => ['ErgazomenoiWTO' => []],
        ]);
    }

    /**
     * Configure with specific employees.
     *
     * @param array<int, \OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee> $employees
     */
    public function withSpecificEmployees(array $employees): static
    {
        return $this->state([
            'Ergazomenoi' => ['ErgazomenoiWTO' => $employees],
        ]);
    }
}
