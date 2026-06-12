<?php

namespace OxygenSuite\OxygenErgani\Factories\Dismissal;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake TrialPeriodTerminationDeclaration (E6LT) models.
 *
 * Note: Does NOT include severance pay - trial period termination requires no compensation.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration>
 */
class TrialPeriodTerminationDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the TrialPeriodTerminationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        // Trial period is typically 12 months maximum
        $hiringDate = $fake->dateTimeBetween('-12 months', '-1 month');
        $terminationDate = new DateTimeImmutable('today');

        return array_merge($this->baseDefinition(), [
            // Employment Dates
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_apolysisdate' => $terminationDate->format('d/m/Y'),

            // Salary (HasSalary)
            'f_apodoxes' => $fake->randomFloat(2, 800, 2000),

            // Form File (HasFormFile)
            'f_file' => '',
        ]);
    }

    // ==================== Salary State Methods ====================

    /**
     * Configure the model with a specific salary.
     */
    public function withSalary(float $amount): static
    {
        return $this->state([
            'f_apodoxes' => $amount,
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

    // ==================== Date State Methods ====================

    /**
     * Configure the model with a specific hiring date.
     */
    public function hireDate(string $date): static
    {
        return $this->state([
            'f_proslipsidate' => $date,
        ]);
    }

    /**
     * Configure the model with a specific termination date.
     */
    public function terminationDate(string $date): static
    {
        return $this->state([
            'f_apolysisdate' => $date,
        ]);
    }

    /**
     * Configure the model with a 12-month trial period ending today.
     */
    public function fullTrialPeriod(): static
    {
        $hiringDate = new DateTimeImmutable('-12 months');
        $terminationDate = new DateTimeImmutable('today');

        return $this->state([
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_apolysisdate' => $terminationDate->format('d/m/Y'),
        ]);
    }
}
