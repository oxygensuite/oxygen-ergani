<?php

namespace OxygenSuite\OxygenErgani\Factories\Dismissal;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Factories\Dismissal\Concerns\HasBaseDefinition;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for generating fake EndOfLoanDeclaration (E6LD) models.
 *
 * Note: Does NOT include salary, severance, or form file - loan termination
 * simply returns the employee to the original employer.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration>
 */
class EndOfLoanDeclarationFactory extends Factory
{
    use HasBaseDefinition;

    /**
     * Define the default attribute values for the EndOfLoanDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $loanFrom = new DateTimeImmutable('-6 months');
        $loanTo = new DateTimeImmutable('today');

        return array_merge($this->baseDefinition(), [
            // Loan Details (HasLoanDetails)
            'f_borrow_type' => (string) LoanType::GENUINE->value,
            'f_borrow_date_from' => $loanFrom->format('d/m/Y'),
            'f_borrow_date_to' => $loanTo->format('d/m/Y'),
            'f_borrow_company_afm' => $fake->afm(),
            'f_borrow_company_eponimia' => strtoupper($fake->company()),
        ]);
    }

    // ==================== Loan Type State Methods ====================

    /**
     * Configure the model as a genuine loan (direct borrowing).
     */
    public function genuineLoan(): static
    {
        return $this->state([
            'f_borrow_type' => (string) LoanType::GENUINE->value,
        ]);
    }

    /**
     * Configure the model as an EPA loan (Temporary Employment Agency).
     */
    public function epaLoan(): static
    {
        return $this->state([
            'f_borrow_type' => (string) LoanType::EPA->value,
        ]);
    }

    // ==================== Loan Period State Methods ====================

    /**
     * Configure the loan period dates.
     */
    public function loanPeriod(string $from, string $to): static
    {
        return $this->state([
            'f_borrow_date_from' => $from,
            'f_borrow_date_to' => $to,
        ]);
    }

    // ==================== Company State Methods ====================

    /**
     * Configure the borrowing company details.
     */
    public function fromCompany(string $afm, string $name): static
    {
        return $this->state([
            'f_borrow_company_afm' => $afm,
            'f_borrow_company_eponimia' => strtoupper($name),
        ]);
    }
}
