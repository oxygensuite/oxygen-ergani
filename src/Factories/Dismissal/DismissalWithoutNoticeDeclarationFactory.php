<?php

namespace OxygenSuite\OxygenErgani\Factories\Dismissal;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake DismissalWithoutNoticeDeclaration (E6NXP) models.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration>
 */
class DismissalWithoutNoticeDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the DismissalWithoutNoticeDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $hiringDate = $fake->dateTimeBetween('-10 years', '-1 month');
        $dismissalDate = new DateTimeImmutable('today');

        return array_merge($this->baseDefinition(), [
            // Employment Classification (HasEmploymentClassification)
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),

            // Collective Dismissal (HasCollectiveDismissal) - not collective by default
            'f_omadiki' => '0',
            'f_omadikiarithmos' => '',
            'f_omadikidate' => '',

            // Employment Dates
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_apolysisdate' => $dismissalDate->format('d/m/Y'),

            // Salary (HasSalary)
            'f_apodoxes' => $fake->randomFloat(2, 800, 3000),

            // Notification Date (HasTerminationNotification)
            'f_koinopoihshdate' => $dismissalDate->format('d/m/Y'),

            // Compensation (HasCompensation)
            'f_posoapozimiosis' => $fake->randomFloat(2, 500, 10000),

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
     * Configure the model for rotation work.
     */
    public function rotationWork(): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::ROTATION->value,
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

    // ==================== Collective Dismissal State Methods ====================

    /**
     * Configure the model as part of a collective dismissal.
     */
    public function asCollectiveDismissal(?string $number = null, ?string $date = null): static
    {
        return $this->state([
            'f_omadiki' => '1',
            'f_omadikiarithmos' => $number ?? self::fake()->bothify('ΑΠ-######'),
            'f_omadikidate' => $date ?? (new DateTimeImmutable('-1 week'))->format('d/m/Y'),
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
