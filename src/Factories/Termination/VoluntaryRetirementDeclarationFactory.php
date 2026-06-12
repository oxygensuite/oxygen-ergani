<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\Concerns\HasBaseDefinition;

/**
 * Factory for generating fake VoluntaryRetirementDeclaration (E5S) models.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration>
 */
class VoluntaryRetirementDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the VoluntaryRetirementDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Override base definition for retirement age
        $fake = self::fake();
        $gender = $fake->randomElement(['male', 'female']);
        $birthDate = $fake->dateTimeBetween('-67 years', '-62 years'); // Retirement age

        $base = $this->baseDefinition();
        $base['f_birthdate'] = $birthDate->format('d/m/Y');
        $base['f_amka'] = $fake->amka($birthDate);

        return array_merge($base, [
            // Salary (HasSalary)
            'f_apodoxes' => self::fake()->randomFloat(2, 800, 3000),

            // Compensation (HasCompensation)
            'f_posoapozimiosis' => self::fake()->randomFloat(2, 5000, 50000),

            // Form File (HasFormFile)
            'f_file' => '',
        ]);
    }

    /**
     * Configure the model with salary at departure.
     */
    public function withSalary(float $amount): static
    {
        return $this->state([
            'f_apodoxes' => $amount,
        ]);
    }

    /**
     * Configure the model with compensation/severance amount.
     */
    public function withCompensation(float $amount): static
    {
        return $this->state([
            'f_posoapozimiosis' => $amount,
        ]);
    }

    /**
     * Configure the model with signed form file.
     */
    public function withFormFile(string $base64Content = 'JVBERi0xLjQK...'): static
    {
        return $this->state([
            'f_file' => $base64Content,
        ]);
    }
}
