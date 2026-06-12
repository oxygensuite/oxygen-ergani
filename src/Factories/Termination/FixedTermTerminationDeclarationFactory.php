<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\FixedTermTerminationReason;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake FixedTermTerminationDeclaration (E7N) models.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration>
 */
class FixedTermTerminationDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the FixedTermTerminationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $hiringDate = $fake->dateTimeBetween('-2 years', '-6 months');
        $contractEndDate = new DateTimeImmutable('+1 month');
        $terminationDate = new DateTimeImmutable('today');

        return array_merge($this->baseDefinition(), [
            // Employment Classification (HasEmploymentClassification)
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_sxeshapasxolisis' => (string) EmploymentType::FIXED_TERM->value, // E7N only allows FIXED_TERM or PROJECT
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),

            // Compensation clause (default: no)
            'f_oros' => '0',

            // Salary (HasSalary)
            'f_apodoxes' => $fake->randomFloat(2, 800, 3000),

            // Employment Dates
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_lixisymbashdate' => $contractEndDate->format('d/m/Y'),
            'f_apolysisdate' => $terminationDate->format('d/m/Y'),

            // Termination reason (default: contract expiration)
            'f_logosperatosis' => (string) FixedTermTerminationReason::CONTRACT_EXPIRATION->value,
            'f_logosperatosiscomments' => '',
        ]);
    }

    // ==================== Employment Relationship State Methods ====================

    /**
     * Configure the model for a fixed-term contract.
     */
    public function fixedTerm(): static
    {
        return $this->state([
            'f_sxeshapasxolisis' => (string) EmploymentType::FIXED_TERM->value,
        ]);
    }

    /**
     * Configure the model for a project-based contract.
     */
    public function projectBased(): static
    {
        return $this->state([
            'f_sxeshapasxolisis' => (string) EmploymentType::PROJECT->value,
        ]);
    }

    // ==================== Compensation Clause State Methods ====================

    /**
     * Configure the model with a compensation clause.
     *
     * Per Article 40 of Law 3986/2011, a fixed-term contract may include a clause
     * that applies indefinite contract severance rules in case of early termination.
     */
    public function withCompensationClause(): static
    {
        return $this->state([
            'f_oros' => '1',
        ]);
    }

    /**
     * Configure the model without a compensation clause.
     */
    public function withoutCompensationClause(): static
    {
        return $this->state([
            'f_oros' => '0',
        ]);
    }

    // ==================== Termination Reason State Methods ====================

    /**
     * Configure the model for natural contract expiration.
     */
    public function expiredContract(): static
    {
        return $this->state([
            'f_logosperatosis' => (string) FixedTermTerminationReason::CONTRACT_EXPIRATION->value,
            'f_logosperatosiscomments' => '',
        ]);
    }

    /**
     * Configure the model for work/project completion.
     */
    public function completedWork(?string $comments = null): static
    {
        return $this->state([
            'f_logosperatosis' => (string) FixedTermTerminationReason::WORK_COMPLETION->value,
            'f_logosperatosiscomments' => $comments ?? '',
        ]);
    }

    /**
     * Configure the model for early termination by employer (with just cause).
     */
    public function terminatedByEmployer(?string $reason = null): static
    {
        return $this->state([
            'f_logosperatosis' => (string) FixedTermTerminationReason::EARLY_BY_EMPLOYER->value,
            'f_logosperatosiscomments' => $reason ?? '',
        ]);
    }

    /**
     * Configure the model for early termination by employee (without just cause).
     */
    public function terminatedByEmployee(?string $reason = null): static
    {
        return $this->state([
            'f_logosperatosis' => (string) FixedTermTerminationReason::EARLY_BY_EMPLOYEE->value,
            'f_logosperatosiscomments' => $reason ?? '',
        ]);
    }

    /**
     * Configure the model for mutual agreement termination.
     */
    public function mutualAgreement(?string $comments = null): static
    {
        return $this->state([
            'f_logosperatosis' => (string) FixedTermTerminationReason::MUTUAL_AGREEMENT->value,
            'f_logosperatosiscomments' => $comments ?? '',
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

    // ==================== Contract Period State Methods ====================

    /**
     * Configure the model with specific contract period dates.
     */
    public function contractPeriod(string $start, string $end): static
    {
        return $this->state([
            'f_proslipsidate' => $start,
            'f_lixisymbashdate' => $end,
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
}
