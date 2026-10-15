<?php

namespace OxygenSuite\OxygenErgani\Factories\Motorcycle;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating MotorcycleVehicle instances with fake data.
 *
 * Creates an employee-owned vehicle with an open-ended use period by default.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleVehicle>
 */
class MotorcycleVehicleFactory extends Factory
{
    /**
     * Define the default attribute values for MotorcycleVehicle.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $letters = ['Α', 'Β', 'Ε', 'Ζ', 'Η', 'Ι', 'Κ', 'Μ', 'Ν', 'Ο', 'Ρ', 'Τ', 'Υ', 'Χ'];

        return [
            'f_licence_plate' => implode('', $fake->randomElements($letters, 3, true)) . $fake->numberBetween(100, 9999),
            'f_is_owner' => '1',
            'f_afm_ergazomenou' => $fake->afm(),
            'f_eponimo_ergazomenou' => $fake->greekLastName(),
            'f_onoma_ergazomenou' => $fake->greekFirstName('male'),
            'f_onoma_patera' => $fake->greekFirstName('male'),
            'f_date_enarxis_xrisis' => $fake->greekDate('now', '+1 month'),
            'f_hour_enarxis_xrisis' => '08:00',
            'f_date_lixis_xrisis' => '',
            'f_paratiriseis' => '',
        ];
    }

    /**
     * Configure as owned, possessed or held by the employer.
     */
    public function employerOwned(): static
    {
        return $this->state([
            'f_is_owner' => '0',
        ]);
    }

    /**
     * Configure with an end date for the vehicle use.
     */
    public function endingOn(string $date): static
    {
        return $this->state([
            'f_date_lixis_xrisis' => $date,
        ]);
    }
}
