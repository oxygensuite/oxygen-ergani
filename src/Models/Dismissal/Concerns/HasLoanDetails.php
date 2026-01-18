<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

use OxygenSuite\OxygenErgani\Enums\LoanType;

/**
 * Employee loan details for end-of-loan declarations.
 *
 * Used in: E6LD only
 */
trait HasLoanDetails
{
    /**
     * Get the type of employee loan arrangement.
     */
    public function getLoanType(): ?int
    {
        $value = $this->get('f_borrow_type');

        return $value !== null ? (int) $value : null;
    }

    /**
     * Set the type of employee loan arrangement.
     *
     * @param LoanType|int $type 0=Genuine loan, 1=EPA (Temporary Employment Agency)
     */
    public function setLoanType(LoanType|int $type): static
    {
        if ($type instanceof LoanType) {
            $type = $type->value;
        }

        return $this->set('f_borrow_type', (string) $type);
    }

    /**
     * Get the loan start date.
     */
    public function getLoanStartDate(): ?string
    {
        return $this->get('f_borrow_date_from');
    }

    /**
     * Set the loan start date.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setLoanStartDate(string $date): static
    {
        return $this->set('f_borrow_date_from', $date);
    }

    /**
     * Get the loan end date.
     */
    public function getLoanEndDate(): ?string
    {
        return $this->get('f_borrow_date_to');
    }

    /**
     * Set the loan end date.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setLoanEndDate(string $date): static
    {
        return $this->set('f_borrow_date_to', $date);
    }

    /**
     * Get the borrowing company's AFM (tax ID).
     */
    public function getBorrowingCompanyAfm(): ?string
    {
        return $this->get('f_borrow_company_afm');
    }

    /**
     * Set the borrowing company's AFM (tax ID).
     *
     * @param string $afm 9-digit AFM
     */
    public function setBorrowingCompanyAfm(string $afm): static
    {
        return $this->set('f_borrow_company_afm', $afm);
    }

    /**
     * Get the borrowing company's name.
     */
    public function getBorrowingCompanyName(): ?string
    {
        return $this->get('f_borrow_company_eponimia');
    }

    /**
     * Set the borrowing company's name.
     *
     * @param string $name Company name (max 230 chars)
     */
    public function setBorrowingCompanyName(string $name): static
    {
        return $this->set('f_borrow_company_eponimia', $name);
    }
}
