<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

use DateTime;

/**
 * Submission header and training-provider fields shared by the E3.1 and E3.2 forms.
 */
trait HasProgramProvider
{
    // ==================== Submission ====================

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_protocol_sx');
    }

    /**
     * Protocol of the original declaration (corrections only).
     */
    public function setRelatedProtocol(string $value): static
    {
        return $this->set('f_protocol_sx', $value);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_date_ypovolis_sx');
    }

    /**
     * Submission date of the original declaration (corrections only).
     */
    public function setRelatedDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_ypovolis_sx', $date);
    }

    public function getDypaServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed');
    }

    /**
     * DYPA service code.
     */
    public function setDypaServiceCode(string $value): static
    {
        return $this->set('f_ypiresia_oaed', $value);
    }

    public function getLaborInspectionCode(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    /**
     * Labour inspectorate service code (5 digits).
     */
    public function setLaborInspectionCode(string $value): static
    {
        return $this->set('f_ypiresia_sepe', $value);
    }

    // ==================== Provider ====================

    public function getEmployerOrganization(): ?string
    {
        return $this->get('f_mer_organization');
    }

    public function setEmployerOrganization(string $value): static
    {
        return $this->set('f_mer_organization', $value);
    }

    public function getMainActivityCode(): ?string
    {
        return $this->get('f_mer_stakod1');
    }

    /**
     * Main activity code (KAD, 4 digits).
     */
    public function setMainActivityCode(string $value): static
    {
        return $this->set('f_mer_stakod1', $value);
    }

    public function getSecondaryActivityCode1(): ?string
    {
        return $this->get('f_mer_stakod2');
    }

    public function setSecondaryActivityCode1(string $value): static
    {
        return $this->set('f_mer_stakod2', $value);
    }

    public function getSecondaryActivityCode2(): ?string
    {
        return $this->get('f_mer_stakod3');
    }

    public function setSecondaryActivityCode2(string $value): static
    {
        return $this->set('f_mer_stakod3', $value);
    }

    public function getSecondaryActivityCode3(): ?string
    {
        return $this->get('f_mer_stakod4');
    }

    public function setSecondaryActivityCode3(string $value): static
    {
        return $this->set('f_mer_stakod4', $value);
    }

    public function getSecondaryActivityCode4(): ?string
    {
        return $this->get('f_mer_stakod5');
    }

    public function setSecondaryActivityCode4(string $value): static
    {
        return $this->set('f_mer_stakod5', $value);
    }

    public function getBranchActivityCode(): ?string
    {
        return $this->get('f_mpa_stakod');
    }

    /**
     * Activity code (KAD) of the branch.
     */
    public function setBranchActivityCode(string $value): static
    {
        return $this->set('f_mpa_stakod', $value);
    }

    public function getMunicipalityCode(): ?string
    {
        return $this->get('f_mpa_kallikratis');
    }

    /**
     * Kallikratis code of the branch (8 digits).
     */
    public function setMunicipalityCode(string $value): static
    {
        return $this->set('f_mpa_kallikratis', $value);
    }
}
