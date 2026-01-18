<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\Concerns\HasBaseDefinition;

/**
 * Factory for generating fake NotificationDeclaration (E5O) models.
 *
 * Note: This declaration does NOT include salary or form file.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration>
 */
class NotificationDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the NotificationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array_merge($this->baseDefinition(), [
            // Notification specific
            'f_tropoi_oxlhshs' => 'Τηλεφωνική επικοινωνία, email',
        ]);
    }

    /**
     * Configure the model with notification methods.
     */
    public function withNotificationMethods(string $methods): static
    {
        return $this->state([
            'f_tropoi_oxlhshs' => $methods,
        ]);
    }
}
