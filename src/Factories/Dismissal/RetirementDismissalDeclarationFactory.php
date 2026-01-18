<?php

namespace OxygenSuite\OxygenErgani\Factories\Dismissal;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake RetirementDismissalDeclaration (E6SXP) models.
 *
 * Note: Does NOT include collective dismissal - retirement dismissals are individual.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration>
 */
class RetirementDismissalDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the RetirementDismissalDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();
        // For retirement, employees typically have longer tenure
        $hiringDate = $fake->dateTimeBetween('-30 years', '-15 years');
        $dismissalDate = new DateTimeImmutable('today');

        // Override birth date for retirement (older employees)
        $birthDate = $fake->dateTimeBetween('-67 years', '-60 years');
        $gender = $fake->randomElement(['male', 'female']);

        $base = $this->baseDefinition();
        // Override birth date in base definition for older employee
        $base['f_birthdate'] = $birthDate->format('d/m/Y');
        $base['f_amka'] = $fake->amka($birthDate);

        return array_merge($base, [
            // Employment Classification (HasEmploymentClassification)
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),

            // Employment Dates
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_apolysisdate' => $dismissalDate->format('d/m/Y'),

            // Salary (HasSalary)
            'f_apodoxes' => $fake->randomFloat(2, 1500, 4000),

            // Notification Date (HasTerminationNotification)
            'f_koinopoihshdate' => $dismissalDate->format('d/m/Y'),

            // Compensation (HasCompensation) - typically higher for retirement
            'f_posoapozimiosis' => $fake->randomFloat(2, 5000, 30000),

            // Form File (HasFormFile)
            'f_file' => '',
        ]);
    }

    // ==================== Employment Classification State Methods ====================

    /**
     * Configure the model as full-time employment.
     */
    public function fullTime(): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
        ]);
    }

    /**
     * Configure the model as part-time employment.
     */
    public function partTime(): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::PARTIAL->value,
        ]);
    }

    /**
     * Configure the model for a blue-collar worker.
     */
    public function asWorker(): static
    {
        return $this->state([
            'f_xaraktirismos' => (string) WorkerType::WORKER->value,
        ]);
    }

    /**
     * Configure the model for a white-collar employee.
     */
    public function asEmployee(): static
    {
        return $this->state([
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
        ]);
    }

    // ==================== Salary and Compensation State Methods ====================

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
     * Configure the model with a specific severance amount.
     */
    public function withSeverance(float $amount): static
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
     * Configure the model with a specific dismissal date.
     */
    public function dismissalDate(string $date): static
    {
        return $this->state([
            'f_apolysisdate' => $date,
            'f_koinopoihshdate' => $date,
        ]);
    }
}
