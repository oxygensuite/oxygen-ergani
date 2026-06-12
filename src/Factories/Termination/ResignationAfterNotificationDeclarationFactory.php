<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\Concerns\HasBaseDefinition;

/**
 * Factory for generating fake ResignationAfterNotificationDeclaration (E5AO) models.
 *
 * This declaration links to a previous E5O notification submission.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration>
 */
class ResignationAfterNotificationDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the ResignationAfterNotificationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array_merge($this->baseDefinition(), [
            // Salary (HasSalary)
            'f_apodoxes' => self::fake()->randomFloat(2, 800, 3000),

            // Notification Reference (HasNotificationReference)
            'f_oxlhsh_protocol' => 'Ε5Ο' . self::fake()->numberBetween(10000, 99999),
            'f_oxlhsh_date_ypovolis' => self::fake()->greekDate('-1 month', '-1 week'),
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
     * Configure the model with notification reference.
     */
    public function withNotificationReference(string $protocol, string $date): static
    {
        return $this->state([
            'f_oxlhsh_protocol' => $protocol,
            'f_oxlhsh_date_ypovolis' => $date,
        ]);
    }
}
