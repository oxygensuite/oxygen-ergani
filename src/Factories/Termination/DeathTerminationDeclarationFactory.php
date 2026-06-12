<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\Concerns\HasBaseDefinition;

/**
 * Factory for generating fake DeathTerminationDeclaration (E5D) models.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration>
 */
class DeathTerminationDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the DeathTerminationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array_merge($this->baseDefinition(), [
            // Salary (HasSalary)
            'f_apodoxes' => self::fake()->randomFloat(2, 800, 3000),

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
     * Configure the model with death certificate file.
     */
    public function withFormFile(string $base64Content = 'JVBERi0xLjQK...'): static
    {
        return $this->state([
            'f_file' => $base64Content,
        ]);
    }
}
