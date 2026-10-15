<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;

/**
 * Beneficiary (f_mea_*) personal, identity, insurance and contact fields shared by the E3.1 and E3.2 forms.
 */
trait HasBeneficiary
{
    // ==================== Beneficiary ====================

    public function getLastName(): ?string
    {
        return $this->get('f_mea_eponimo');
    }

    public function setLastName(string $value): static
    {
        return $this->set('f_mea_eponimo', $value);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_mea_onoma');
    }

    public function setFirstName(string $value): static
    {
        return $this->set('f_mea_onoma', $value);
    }

    public function getFatherLastName(): ?string
    {
        return $this->get('f_mea_eponimo_patera');
    }

    public function setFatherLastName(string $value): static
    {
        return $this->set('f_mea_eponimo_patera', $value);
    }

    public function getFatherName(): ?string
    {
        return $this->get('f_mea_onoma_patera');
    }

    public function setFatherName(string $value): static
    {
        return $this->set('f_mea_onoma_patera', $value);
    }

    public function getMotherLastName(): ?string
    {
        return $this->get('f_mea_eponimo_miteras');
    }

    public function setMotherLastName(string $value): static
    {
        return $this->set('f_mea_eponimo_miteras', $value);
    }

    public function getMotherName(): ?string
    {
        return $this->get('f_mea_onoma_miteras');
    }

    public function setMotherName(string $value): static
    {
        return $this->set('f_mea_onoma_miteras', $value);
    }

    public function getSex(): ?string
    {
        return $this->get('f_mea_sex');
    }

    /**
     * @param Sex|string|int $value 0=Male, 1=Female (or use Sex enum)
     */
    public function setSex(Sex|string|int $value): static
    {
        if ($value instanceof Sex) {
            $value = $value->value;
        }

        return $this->set('f_mea_sex', (string) $value);
    }

    public function getBirthPlace(): ?string
    {
        return $this->get('f_mea_birth_city');
    }

    public function setBirthPlace(string $value): static
    {
        return $this->set('f_mea_birth_city', $value);
    }

    public function getBirthDate(): ?string
    {
        return $this->get('f_mea_date_birth');
    }

    public function setBirthDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_date_birth', $date);
    }

    public function getNationality(): ?string
    {
        return $this->get('f_mea_nationality');
    }

    /**
     * Nationality code (3 digits, see ParameterLookup::NATIONALITY).
     */
    public function setNationality(string $value): static
    {
        return $this->set('f_mea_nationality', $value);
    }

    // ==================== Identity ====================

    public function getIdType(): ?string
    {
        return $this->get('f_mea_typos_taytotitas');
    }

    /**
     * Identity document type (see ParameterLookup::ID_TYPE).
     */
    public function setIdType(string $value): static
    {
        return $this->set('f_mea_typos_taytotitas', $value);
    }

    public function getIdNumber(): ?string
    {
        return $this->get('f_mea_ar_taytotitas');
    }

    public function setIdNumber(string $value): static
    {
        return $this->set('f_mea_ar_taytotitas', $value);
    }

    public function getIdIssuingAuthority(): ?string
    {
        return $this->get('f_mea_ekdousa');
    }

    public function setIdIssuingAuthority(string $value): static
    {
        return $this->set('f_mea_ekdousa', $value);
    }

    public function getIdIssueDate(): ?string
    {
        return $this->get('f_mea_date_ekdosis');
    }

    public function setIdIssueDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_date_ekdosis', $date);
    }

    public function getIdExpiryDate(): ?string
    {
        return $this->get('f_mea_date_ekdosis_lixi');
    }

    public function setIdExpiryDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_date_ekdosis_lixi', $date);
    }

    public function getResidencePermit(): ?string
    {
        return $this->get('f_mea_res_permit');
    }

    /**
     * Whether the beneficiary holds a residence or work permit.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setResidencePermit(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_mea_res_permit', $value);
    }

    public function getResidencePermitType(): ?string
    {
        return $this->get('f_mea_res_permit_type');
    }

    /**
     * Residence permit type (see ParameterLookup::RESIDENCE_PERMIT).
     */
    public function setResidencePermitType(string $value): static
    {
        return $this->set('f_mea_res_permit_type', $value);
    }

    public function getResidencePermitNumber(): ?string
    {
        return $this->get('f_mea_res_permit_ar');
    }

    public function setResidencePermitNumber(string $value): static
    {
        return $this->set('f_mea_res_permit_ar', $value);
    }

    public function getResidencePermitExpiry(): ?string
    {
        return $this->get('f_mea_res_permit_lixi');
    }

    public function setResidencePermitExpiry(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_res_permit_lixi', $date);
    }

    // ==================== Family, Tax & Insurance ====================

    public function getMaritalStatus(): ?string
    {
        return $this->get('f_mea_marital_status');
    }

    /**
     * @param MaritalStatus|string|int $value 0=Single, 1=Married, 2=Divorced, 3=Widowed (or use MaritalStatus enum)
     */
    public function setMaritalStatus(MaritalStatus|string|int $value): static
    {
        if ($value instanceof MaritalStatus) {
            $value = $value->value;
        }

        return $this->set('f_mea_marital_status', (string) $value);
    }

    public function getNumberOfChildren(): ?string
    {
        return $this->get('f_mea_ar_teknon');
    }

    public function setNumberOfChildren(int|string $value): static
    {
        return $this->set('f_mea_ar_teknon', (string) $value);
    }

    public function getAfm(): ?string
    {
        return $this->get('f_mea_afm');
    }

    public function setAfm(string $value): static
    {
        return $this->set('f_mea_afm', $value);
    }

    public function getTaxOffice(): ?string
    {
        return $this->get('f_mea_doy');
    }

    /**
     * Tax office code (4 digits, see ParameterLookup::DOY).
     */
    public function setTaxOffice(string $value): static
    {
        return $this->set('f_mea_doy', $value);
    }

    public function getInsuranceAgency(): ?string
    {
        return $this->get('f_mea_insurance_agency');
    }

    /**
     * Main insurance agency code (see ParameterLookup::PRIMARY_INSURANCE).
     */
    public function setInsuranceAgency(string $value): static
    {
        return $this->set('f_mea_insurance_agency', $value);
    }

    public function getAmika(): ?string
    {
        return $this->get('f_mea_am_ika');
    }

    /**
     * e-EFKA insurance registry number.
     */
    public function setAmika(string $value): static
    {
        return $this->set('f_mea_am_ika', $value);
    }

    public function getAmka(): ?string
    {
        return $this->get('f_mea_amka');
    }

    public function setAmka(string $value): static
    {
        return $this->set('f_mea_amka', $value);
    }

    public function getUnemploymentCardNumber(): ?string
    {
        return $this->get('f_mea_code_anergias');
    }

    public function setUnemploymentCardNumber(string $value): static
    {
        return $this->set('f_mea_code_anergias', $value);
    }

    // ==================== Contact ====================

    public function getAddress(): ?string
    {
        return $this->get('f_mea_dieythinsi');
    }

    public function setAddress(string $value): static
    {
        return $this->set('f_mea_dieythinsi', $value);
    }

    public function getResidenceMunicipalityCode(): ?string
    {
        return $this->get('f_mea_kallikratis');
    }

    /**
     * Kallikratis code of the beneficiary residence (8 digits).
     */
    public function setResidenceMunicipalityCode(string $value): static
    {
        return $this->set('f_mea_kallikratis', $value);
    }

    public function getPostalCode(): ?string
    {
        return $this->get('f_mea_tk');
    }

    public function setPostalCode(string $value): static
    {
        return $this->set('f_mea_tk', $value);
    }

    public function getPhone(): ?string
    {
        return $this->get('f_mea_til');
    }

    public function setPhone(string $value): static
    {
        return $this->set('f_mea_til', $value);
    }

    public function getFax(): ?string
    {
        return $this->get('f_mea_fax');
    }

    public function setFax(string $value): static
    {
        return $this->set('f_mea_fax', $value);
    }

    public function getEmail(): ?string
    {
        return $this->get('f_mea_email');
    }

    public function setEmail(string $value): static
    {
        return $this->set('f_mea_email', $value);
    }

    public function getEducationLevel(): ?string
    {
        return $this->get('f_mea_epipedo_morfosis');
    }

    /**
     * Education level code (see ParameterLookup::EDUCATION_LEVEL).
     */
    public function setEducationLevel(string $value): static
    {
        return $this->set('f_mea_epipedo_morfosis', $value);
    }
}
