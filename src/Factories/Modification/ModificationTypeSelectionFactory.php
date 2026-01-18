<?php

namespace OxygenSuite\OxygenErgani\Factories\Modification;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;

/**
 * Factory for generating fake ModificationTypeSelection models.
 *
 * @extends Factory<ModificationTypeSelection>
 */
class ModificationTypeSelectionFactory extends Factory
{
    /**
     * Common modification type codes.
     *
     * @var array<int, string>
     */
    private const CODES = [
        '01', // Salary change
        '02', // Position change
        '03', // Hours change
        '04', // Location change
        '05', // Employment status change
    ];

    /**
     * Define the default attribute values.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_typos_metabolhs' => fake()->randomElement(self::CODES),
        ];
    }

    /**
     * Set a specific modification type code.
     *
     * @return $this
     */
    public function code(string $code): static
    {
        return $this->state([
            'f_typos_metabolhs' => $code,
        ]);
    }
}
