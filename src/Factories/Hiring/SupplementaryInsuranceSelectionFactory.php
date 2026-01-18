<?php

namespace OxygenSuite\OxygenErgani\Factories\Hiring;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;

/**
 * Factory for generating fake SupplementaryInsuranceSelection models.
 *
 * @extends Factory<SupplementaryInsuranceSelection>
 */
class SupplementaryInsuranceSelectionFactory extends Factory
{
    /**
     * Common supplementary insurance codes.
     *
     * @var array<int, string>
     */
    private const CODES = [
        '201', // ΕΤΕΑΕΠ - Επικουρική
        '202', // ΕΤΕΑΕΠ - Εφάπαξ
        '301', // ΤΕΚΑ
        '401', // Ταμείο Νομικών
        '501', // Ταμείο Μηχανικών
    ];

    /**
     * Define the default attribute values.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_kod_epikourikis' => fake()->randomElement(self::CODES),
        ];
    }

    /**
     * Set a specific insurance code.
     *
     * @return $this
     */
    public function code(string $code): static
    {
        return $this->state([
            'f_kod_epikourikis' => $code,
        ]);
    }
}
