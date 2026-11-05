<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Beneficiary AFM (`f_mea_afm`), shared by the full E3.1 - E3.4 forms (through {@see HasBeneficiary})
 * and the follow-up forms that only identify the beneficiary (E3.1 extension/interruption,
 * E3.2 employer change/interruption).
 */
trait HasBeneficiaryAfm
{
    public function getAfm(): ?string
    {
        return $this->get('f_mea_afm');
    }

    /**
     * AFM of the beneficiary.
     */
    public function setAfm(string $value): static
    {
        return $this->set('f_mea_afm', $value);
    }
}
