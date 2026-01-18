<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkingStatus;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating WorkingStatus instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus>
 */
class WorkingStatusFactory extends Factory
{
    /**
     * Define the default attribute values for WorkingStatus.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_aa_pararthmatos' => (string) fake()->numberBetween(0, 99),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_comments' => '',
            'Ergazomenoi' => fn() => ['Ergazomenos' => [WorkingStatusEmployeeFactory::new()->make()]],
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
            'Ergazomenoi' => fn() => ['Ergazomenos' => WorkingStatusEmployeeFactory::new()->count($count)->make()],
        ]);
    }

    /**
     * Configure without any employees.
     */
    public function withoutEmployees(): static
    {
        return $this->state([
            'Ergazomenoi' => ['Ergazomenos' => []],
        ]);
    }

    /**
     * Configure with specific employees.
     *
     * @param array<int, \OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatusEmployee> $employees
     */
    public function withSpecificEmployees(array $employees): static
    {
        return $this->state([
            'Ergazomenoi' => ['Ergazomenos' => $employees],
        ]);
    }
}
