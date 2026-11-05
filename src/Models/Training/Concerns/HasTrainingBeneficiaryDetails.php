<?php

namespace OxygenSuite\OxygenErgani\Models\Training\Concerns;

use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorBookNumber;

/**
 * Invitation code and beneficiary training-profile fields shared by
 * E3.2 (WebE32/WebE32C) and E3.3 (WebE33/WebE33C).
 */
trait HasTrainingBeneficiaryDetails
{
    use HasMinorBookNumber;

    public function getInvitationCode(): ?string
    {
        return $this->get('f_programma_voucher');
    }

    /**
     * Invitation (call) code (3 digits).
     */
    public function setInvitationCode(string $value): static
    {
        return $this->set('f_programma_voucher', $value);
    }

    public function getVocationalTrainingSubject(): ?string
    {
        return $this->get('f_mea_practical_education');
    }

    /**
     * Vocational training subject code (3 digits).
     */
    public function setVocationalTrainingSubject(string $value): static
    {
        return $this->set('f_mea_practical_education', $value);
    }

    public function getVoucherProgram(): ?string
    {
        return $this->get('f_mea_voucher');
    }

    /**
     * Whether this is a voucher program.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setVoucherProgram(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_mea_voucher', $value);
    }

    public function getKayasCode(): ?string
    {
        return $this->get('f_mea_kayas');
    }

    /**
     * K.A.Y.A.S. code.
     */
    public function setKayasCode(string $value): static
    {
        return $this->set('f_mea_kayas', $value);
    }
}
