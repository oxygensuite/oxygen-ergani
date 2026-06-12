<?php

namespace OxygenSuite\OxygenErgani\Factories\PreAnnouncement;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ExemptionDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration>
 */
class ExemptionDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for ExemptionDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_aa_pararthmatos' => (string) self::fake()->numberBetween(0, 99),
            'f_is_excluded' => '1',
            'f_month' => str_pad((string) self::fake()->numberBetween(1, 12), 2, '0', STR_PAD_LEFT),
            'f_year' => (string) date('Y'),
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
     * Configure as excluded from pre-announcement.
     */
    public function excluded(): static
    {
        return $this->state([
            'f_is_excluded' => '1',
        ]);
    }

    /**
     * Configure as not excluded from pre-announcement.
     */
    public function notExcluded(): static
    {
        return $this->state([
            'f_is_excluded' => '0',
        ]);
    }

    /**
     * Configure for a specific month and year.
     */
    public function forPeriod(int $month, int $year): static
    {
        return $this->state([
            'f_month' => str_pad((string) $month, 2, '0', STR_PAD_LEFT),
            'f_year' => (string) $year,
        ]);
    }
}
