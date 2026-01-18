<?php

namespace OxygenSuite\OxygenErgani\Factories\Dismissal;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake TransferDeclaration (E6M) models.
 *
 * Note: Does NOT include salary, severance, or form file - transfer
 * simply moves the employee to another company.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration>
 */
class TransferDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the TransferDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();

        return array_merge($this->baseDefinition(), [
            // Transfer Details
            'f_date_metabibashs' => (new DateTimeImmutable('today'))->format('d/m/Y'),
            'f_transfer_company_afm' => $fake->afm(),
            'f_transfer_company_eponimia' => strtoupper($fake->company()),
        ]);
    }

    // ==================== Transfer Date State Methods ====================

    /**
     * Configure the transfer date.
     */
    public function transferDate(string $date): static
    {
        return $this->state([
            'f_date_metabibashs' => $date,
        ]);
    }

    // ==================== Company State Methods ====================

    /**
     * Configure the receiving company details.
     */
    public function toCompany(string $afm, string $name): static
    {
        return $this->state([
            'f_transfer_company_afm' => $afm,
            'f_transfer_company_eponimia' => strtoupper($name),
        ]);
    }
}
