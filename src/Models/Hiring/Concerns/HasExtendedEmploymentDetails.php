<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\SpecialCase;

/**
 * Extended employment details shared by E3N, E3M, and E3PD schemas.
 *
 * Provides methods for hiring date, experience, salary, employment type,
 * fixed-term contract dates, and special case handling.
 */
trait HasExtendedEmploymentDetails
{
    /**
     * Get the employee's hiring date.
     */
    public function getHiringDate(): ?string
    {
        return $this->get('f_proslipsidate');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setHiringDate(string $date): static
    {
        return $this->set('f_proslipsidate', $date);
    }

    /**
     * Get the employee's years of prior experience.
     */
    public function getExperienceYears(): ?int
    {
        return $this->int('f_proipiresia');
    }

    /**
     * @param int $years Years of experience (1-3 digits)
     */
    public function setExperienceYears(int $years): static
    {
        return $this->set('f_proipiresia', $years);
    }

    /**
     * Get the total gross monthly salary.
     */
    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    /**
     * @param float $amount Salary amount (e.g., 1500.00)
     */
    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    /**
     * Get the hourly wage.
     */
    public function getHourlyWage(): ?float
    {
        return $this->greekFloat('f_hour_apodoxes');
    }

    /**
     * @param float $amount Hourly wage (e.g., 10.50)
     */
    public function setHourlyWage(float $amount): static
    {
        return $this->set('f_hour_apodoxes', $amount);
    }

    /**
     * Get the employment relationship type.
     */
    public function getEmploymentType(): ?string
    {
        return $this->get('f_sxeshapasxolisis');
    }

    /**
     * @param EmploymentType|string|int $type 0=Indefinite duration, 1=Fixed-term (or use EmploymentType enum)
     */
    public function setEmploymentType(EmploymentType|string|int $type): static
    {
        if ($type instanceof EmploymentType) {
            $type = $type->value;
        }

        return $this->set('f_sxeshapasxolisis', (string) $type);
    }

    /**
     * Get the fixed-term contract start date.
     */
    public function getFixedTermFrom(): ?string
    {
        return $this->get('f_orismenou_apo');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setFixedTermFrom(string $date): static
    {
        return $this->set('f_orismenou_apo', $date);
    }

    /**
     * Get the fixed-term contract end date.
     */
    public function getFixedTermTo(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setFixedTermTo(string $date): static
    {
        return $this->set('f_orismenou_ews', $date);
    }

    /**
     * Get special employment case for public sector employees.
     */
    public function getSpecialCase(): ?string
    {
        return $this->get('f_special_case');
    }

    /**
     * @param SpecialCase|string $case 2=Private law (public sector), 3=Private law (broader public sector)
     */
    public function setSpecialCase(SpecialCase|string $case): static
    {
        if ($case instanceof SpecialCase) {
            $case = $case->value;
        }

        return $this->set('f_special_case', $case);
    }
}
