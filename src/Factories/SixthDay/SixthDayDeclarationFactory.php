<?php

namespace OxygenSuite\OxygenErgani\Factories\SixthDay;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating SixthDayDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration>
 */
class SixthDayDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for SixthDayDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = new DateTimeImmutable('next saturday');

        return [
            'f_aa_pararthmatos' => (string) fake()->numberBetween(0, 99),
            'f_continuous_operation' => '0',
            'f_kad_kyria' => (string) fake()->numberBetween(10000, 99999),
            'f_special_occasion_description' => '',
            'f_date_special_from' => $date->format('d/m/Y'),
            'f_date_special_to' => $date->format('d/m/Y'),
            'f_comments' => '',
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
     * Configure as continuous operation.
     */
    public function continuousOperation(): static
    {
        return $this->state([
            'f_continuous_operation' => '1',
        ]);
    }

    /**
     * Configure with a special occasion description.
     */
    public function withSpecialOccasion(string $description): static
    {
        return $this->state([
            'f_special_occasion_description' => $description,
        ]);
    }

    /**
     * Configure for a specific date range.
     */
    public function forDateRange(string $from, string $to): static
    {
        return $this->state([
            'f_date_special_from' => $from,
            'f_date_special_to' => $to,
        ]);
    }
}
