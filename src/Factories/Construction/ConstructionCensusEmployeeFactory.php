<?php

namespace OxygenSuite\OxygenErgani\Factories\Construction;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ConstructionCensusEmployee instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensusEmployee>
 */
class ConstructionCensusEmployeeFactory extends Factory
{
    /**
     * Define the default attribute values for ConstructionCensusEmployee.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_afm' => $fake->afm(),
            'f_amka' => $fake->amka(),
            'f_ama' => $fake->amika(),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName('male'),
            'f_onoma_patera' => $fake->greekFirstName('male'),
            'f_days_worked' => (string) $fake->numberBetween(1, 26),
            'f_step' => (string) $fake->numberBetween(100000, 999999),
            'f_ar_adeias' => '',
            'f_hire_date' => $fake->greekDate('-2 years', '-1 month'),
            'f_apodoxes' => (float) $fake->numberBetween(800, 2500),
            'f_notes' => '',
        ];
    }

    /**
     * Configure with specific days worked.
     */
    public function withDaysWorked(int $days): static
    {
        return $this->state([
            'f_days_worked' => (string) $days,
        ]);
    }

    /**
     * Configure with specific gross earnings.
     */
    public function withGrossEarnings(float $amount): static
    {
        return $this->state([
            'f_apodoxes' => $amount,
        ]);
    }

    /**
     * Configure with a work permit number.
     */
    public function withWorkPermit(string $number): static
    {
        return $this->state([
            'f_ar_adeias' => $number,
        ]);
    }
}
