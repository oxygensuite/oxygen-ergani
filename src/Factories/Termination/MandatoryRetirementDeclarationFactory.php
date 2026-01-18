<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\Concerns\HasBaseDefinition;

/**
 * Factory for generating fake MandatoryRetirementDeclaration (E5DS) models.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration>
 */
class MandatoryRetirementDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the MandatoryRetirementDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Override base definition for mandatory retirement age (typically 67+)
        $fake = fake();
        $gender = $fake->randomElement(['male', 'female']);
        $birthDate = $fake->dateTimeBetween('-70 years', '-67 years');

        $base = $this->baseDefinition();
        $base['f_birthdate'] = $birthDate->format('d/m/Y');
        $base['f_amka'] = $fake->amka($birthDate);

        return array_merge($base, [
            // Salary (HasSalary)
            'f_apodoxes' => fake()->randomFloat(2, 800, 3000),

            // Compensation (HasCompensation)
            'f_posoapozimiosis' => fake()->randomFloat(2, 5000, 50000),

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
