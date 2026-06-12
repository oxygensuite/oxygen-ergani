<?php

namespace OxygenSuite\OxygenErgani\Factories\Construction;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating ConstructionEmployee instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Construction\ConstructionEmployee>
 */
class ConstructionEmployeeFactory extends Factory
{
    /**
     * Define the default attribute values for ConstructionEmployee.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $startTime = $fake->time24h();

        return [
            'f_afm' => $fake->afm(),
            'f_amka' => $fake->amka(),
            'f_ama' => $fake->amika(),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName('male'),
            'f_onoma_patera' => $fake->greekFirstName('male'),
            'f_date' => $fake->greekDate('-1 week', 'now'),
            'f_from' => $startTime,
            'f_to' => $fake->workEndTime($startTime),
            'f_cancellation' => '0',
            'f_step' => (string) $fake->numberBetween(100000, 999999),
            'f_ar_adeias' => '',
            'f_hire_date' => $fake->greekDate('-2 years', '-1 month'),
            'f_apodoxes' => (float) $fake->numberBetween(50, 200),
            'f_notes' => '',
        ];
    }

    /**
     * Configure as a cancellation entry.
     */
    public function asCancellation(): static
    {
        return $this->state([
            'f_cancellation' => '1',
        ]);
    }

    /**
     * Configure for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state([
            'f_date' => $date,
        ]);
    }

    /**
     * Configure with specific work hours.
     */
    public function withHours(string $from, string $to): static
    {
        return $this->state([
            'f_from' => $from,
            'f_to' => $to,
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

    /**
     * Configure with specific daily wage.
     */
    public function withDailyWage(float $amount): static
    {
        return $this->state([
            'f_apodoxes' => $amount,
        ]);
    }
}
