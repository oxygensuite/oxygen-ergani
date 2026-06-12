<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;

/**
 * Insurance fields shared by E3N, E3M, and E3PD schemas.
 *
 * Provides methods for main insurance, supplementary insurance selections,
 * and additional insurance benefits.
 */
trait HasInsurance
{
    /**
     * Get the primary/main insurance code.
     */
    public function getMainInsurance(): ?string
    {
        return $this->get('f_kyria_asfalisi');
    }

    /**
     * @param string $code Main insurance code (1-10 digits)
     */
    public function setMainInsurance(string $code): static
    {
        return $this->set('f_kyria_asfalisi', $code);
    }

    /**
     * Get the supplementary insurance selections.
     *
     * @return SupplementaryInsuranceSelection[]|null
     */
    public function getSupplementaryInsuranceSelections(): ?array
    {
        $selections = $this->get('SupplementaryInsuranceSelections');

        return is_array($selections) ? $selections : null;
    }

    /**
     * @param array<int, SupplementaryInsuranceSelection> $selections Array of supplementary insurance codes
     */
    public function setSupplementaryInsuranceSelections(array $selections): static
    {
        return $this->set('SupplementaryInsuranceSelections', $selections);
    }

    /**
     * @param SupplementaryInsuranceSelection $selection Supplementary insurance to add
     */
    public function addSupplementaryInsurance(SupplementaryInsuranceSelection $selection): static
    {
        $selections = $this->get('SupplementaryInsuranceSelections') ?? [];
        $selections[] = $selection;

        return $this->set('SupplementaryInsuranceSelections', $selections);
    }

    /**
     * Get additional insurance benefits description.
     */
    public function getAdditionalInsuranceBenefits(): ?string
    {
        return $this->get('f_prosthetes_asfalistikes');
    }

    /**
     * @param string $benefits Benefits description (max 500 chars)
     */
    public function setAdditionalInsuranceBenefits(string $benefits): static
    {
        return $this->set('f_prosthetes_asfalistikes', $benefits);
    }
}
