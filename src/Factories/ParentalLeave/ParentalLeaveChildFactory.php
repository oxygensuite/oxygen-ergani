<?php

namespace OxygenSuite\OxygenErgani\Factories\ParentalLeave;

use DateTime;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ParentalLeaveChild instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveChild>
 */
class ParentalLeaveChildFactory extends Factory
{
    /**
     * Define the default attribute values for ParentalLeaveChild.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_amka' => self::fake()->amka(new DateTime('-3 years')),
            'f_xorhghsh_adeias' => '1',
            'f_polydymh_kyhsh' => '0',
        ];
    }

    /**
     * Configure as a sibling the leave is not granted for.
     */
    public function notGranted(): static
    {
        return $this->state([
            'f_xorhghsh_adeias' => '0',
        ]);
    }

    /**
     * Configure as a child from a multiple birth.
     */
    public function multipleBirth(): static
    {
        return $this->state([
            'f_polydymh_kyhsh' => '1',
        ]);
    }
}
