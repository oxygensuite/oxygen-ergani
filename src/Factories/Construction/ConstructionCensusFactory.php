<?php

namespace OxygenSuite\OxygenErgani\Factories\Construction;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ConstructionCensus instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus>
 */
class ConstructionCensusFactory extends Factory
{
    /**
     * Define the default attribute values for ConstructionCensus.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $month = $fake->numberBetween(1, 12);

        return [
            'f_aa_pararthmatos' => (string) $fake->numberBetween(0, 99),
            'f_amoe' => (string) $fake->numberBetween(1000000000, 9999999999),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_date_from' => '01/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '/' . date('Y'),
            'f_date_to' => '28/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '/' . date('Y'),
            'f_phase' => '',
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_year' => (string) date('Y'),
            'f_month' => (string) $month,
            'f_kallikratis_pararthmatos' => (string) $fake->numberBetween(10000000, 99999999),
            'f_comments' => '',
            'Ergazomenoi' => fn() => ['AmoeErgazomenosDate' => [ConstructionCensusEmployeeFactory::new()->make()]],
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
     * Configure for a specific period.
     */
    public function forPeriod(int $month, int $year): static
    {
        return $this->state([
            'f_year' => (string) $year,
            'f_month' => (string) $month,
            'f_date_from' => '01/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '/' . $year,
            'f_date_to' => '28/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '/' . $year,
        ]);
    }

    /**
     * Configure with a construction phase.
     */
    public function withPhase(string $phase): static
    {
        return $this->state([
            'f_phase' => $phase,
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
            'Ergazomenoi' => fn() => ['AmoeErgazomenosDate' => ConstructionCensusEmployeeFactory::new()->count($count)->make()],
        ]);
    }

    /**
     * Configure without any employees.
     */
    public function withoutEmployees(): static
    {
        return $this->state([
            'Ergazomenoi' => ['AmoeErgazomenosDate' => []],
        ]);
    }

    /**
     * Configure with specific employees.
     *
     * @param array<int, \OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensusEmployee> $employees
     */
    public function withSpecificEmployees(array $employees): static
    {
        return $this->state([
            'Ergazomenoi' => ['AmoeErgazomenosDate' => $employees],
        ]);
    }
}
