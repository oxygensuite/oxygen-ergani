<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Abstract base class for employer-initiated termination (dismissal) declarations.
 *
 * Contains common fields shared by all E6 dismissal schemas:
 * - E6NXP_v1 (DismissalWithoutNoticeDeclaration)
 * - E6NMP_v1 (DismissalWithNoticeDeclaration)
 * - E6SXP_v1 (RetirementDismissalDeclaration)
 * - E6LD_v1 (EndOfLoanDeclaration)
 * - E6LT_v1 (TrialPeriodTerminationDeclaration)
 *
 * Note: E6 forms use f_apolysisdate (dismissal date) instead of E5's f_apoxwrisidate (departure date).
 * E6 forms do NOT have f_sxeshapasxolisis or f_orismenou_* fields that E5 forms have.
 *
 * @see xsd/E6*_v1.xsd
 */
abstract class Declaration extends Model
{
    // ==================== Branch/Location ====================

    /**
     * Get the branch number (A/A ΠΑΡΑΡΤΗΜΑΤΟΣ).
     */
    public function getBranchCode(): ?string
    {
        return $this->get('f_aa_pararthmatos');
    }

    /**
     * @param string|int $code Branch code (1-5 digits)
     */
    public function setBranchCode(string|int $code): static
    {
        return $this->set('f_aa_pararthmatos', (string) $code);
    }

    /**
     * Get the related document protocol number for modifications/corrections.
     */
    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_rel_protocol');
    }

    /**
     * @param string $protocol Protocol number (max 50 chars)
     */
    public function setRelatedProtocol(string $protocol): static
    {
        return $this->set('f_rel_protocol', $protocol);
    }

    /**
     * Get the related document date for modifications/corrections.
     */
    public function getRelatedDate(): ?string
    {
        return $this->get('f_rel_date');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setRelatedDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_rel_date', $date);
    }

    /**
     * Get the Labor Inspection Service (SEPE) code.
     */
    public function getLaborInspectionServiceCode(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    /**
     * @param string $code 5-digit SEPE service code
     */
    public function setLaborInspectionServiceCode(string $code): static
    {
        return $this->set('f_ypiresia_sepe', $code);
    }

    /**
     * Get the Public Employment Service (DYPA/OAED) code.
     */
    public function getDypaServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed');
    }

    /**
     * @param string $code 6-digit DYPA service code
     */
    public function setDypaServiceCode(string $code): static
    {
        return $this->set('f_ypiresia_oaed', $code);
    }

    /**
     * Get the branch's economic activity code (KAD).
     */
    public function getBranchActivityCode(): ?string
    {
        return $this->get('f_kad_pararthmatos');
    }

    /**
     * @param string $code 4-digit KAD code
     */
    public function setBranchActivityCode(string $code): static
    {
        return $this->set('f_kad_pararthmatos', $code);
    }

    /**
     * Get the municipal/local community code (Kallikratis).
     */
    public function getMunicipalityCode(): ?string
    {
        return $this->get('f_kallikratis_pararthmatos');
    }

    /**
     * @param string $code 8-digit Kallikratis code
     */
    public function setMunicipalityCode(string $code): static
    {
        return $this->set('f_kallikratis_pararthmatos', $code);
    }

    // ==================== Personal Information ====================

    /**
     * Get the employee's last name/surname.
     */
    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    /**
     * @param string $lastName Last name (max 50 chars)
     */
    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    /**
     * Get the employee's first name.
     */
    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    /**
     * @param string $firstName First name (max 30 chars)
     */
    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    /**
     * Get the employee's father's first name.
     */
    public function getFatherName(): ?string
    {
        return $this->get('f_onoma_patros');
    }

    /**
     * @param string $name Father's first name (max 30 chars)
     */
    public function setFatherName(string $name): static
    {
        return $this->set('f_onoma_patros', $name);
    }

    /**
     * Get the employee's mother's first name.
     */
    public function getMotherName(): ?string
    {
        return $this->get('f_onoma_mitros');
    }

    /**
     * @param string $name Mother's first name (max 30 chars)
     */
    public function setMotherName(string $name): static
    {
        return $this->set('f_onoma_mitros', $name);
    }

    /**
     * Get the employee's date of birth.
     */
    public function getBirthDate(): ?string
    {
        return $this->get('f_birthdate');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setBirthDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_birthdate', $date);
    }

    /**
     * Get the employee's sex/gender.
     */
    public function getSex(): ?string
    {
        return $this->get('f_sex');
    }

    /**
     * @param Sex|string|int $sex 0=Male, 1=Female (or use Sex enum)
     */
    public function setSex(Sex|string|int $sex): static
    {
        if ($sex instanceof Sex) {
            $sex = $sex->value;
        }

        return $this->set('f_sex', (string) $sex);
    }

    // ==================== Identity/Nationality ====================

    /**
     * Get the employee's nationality code.
     */
    public function getNationality(): ?string
    {
        return $this->get('f_yphkoothta');
    }

    /**
     * @param string $code 3-digit nationality code
     */
    public function setNationality(string $code): static
    {
        return $this->set('f_yphkoothta', $code);
    }

    /**
     * Get the ID document type code.
     */
    public function getIdType(): ?string
    {
        return $this->get('f_typos_taytothtas');
    }

    /**
     * @param string $type ID type code (1-10 uppercase letters)
     */
    public function setIdType(string $type): static
    {
        return $this->set('f_typos_taytothtas', $type);
    }

    /**
     * Get the ID document number.
     */
    public function getIdNumber(): ?string
    {
        return $this->get('f_ar_taytothtas');
    }

    /**
     * @param string $number ID number (max 20 chars)
     */
    public function setIdNumber(string $number): static
    {
        return $this->set('f_ar_taytothtas', $number);
    }

    /**
     * Get the ID issuing authority.
     */
    public function getIdIssuingAuthority(): ?string
    {
        return $this->get('f_ekdousa_arxh');
    }

    /**
     * @param string $authority Issuing authority name (max 50 chars)
     */
    public function setIdIssuingAuthority(string $authority): static
    {
        return $this->set('f_ekdousa_arxh', $authority);
    }

    /**
     * Get the ID document issue date.
     */
    public function getIdIssueDate(): ?string
    {
        return $this->get('f_date_ekdosis');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setIdIssueDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_ekdosis', $date);
    }

    /**
     * Get the ID document expiry date.
     */
    public function getIdExpiryDate(): ?string
    {
        return $this->get('f_date_ekdosis_lixi');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setIdExpiryDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_ekdosis_lixi', $date);
    }

    // ==================== Residence Permits (Direct Access) ====================

    /**
     * Whether employee has a residence permit with direct labor market access.
     */
    public function getResPermitDirectAccess(): ?string
    {
        return $this->get('f_res_permit_inst');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setResPermitDirectAccess(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_res_permit_inst', $value);
    }

    /**
     * Get the residence permit type (direct access).
     */
    public function getResPermitDirectAccessType(): ?string
    {
        return $this->get('f_res_permit_inst_type');
    }

    /**
     * @param string $type Permit type code (max 5 digits)
     */
    public function setResPermitDirectAccessType(string $type): static
    {
        return $this->set('f_res_permit_inst_type', $type);
    }

    /**
     * Get the residence permit number (direct access).
     */
    public function getResPermitDirectAccessNumber(): ?string
    {
        return $this->get('f_res_permit_inst_ar');
    }

    /**
     * @param string $number Permit number (max 20 chars)
     */
    public function setResPermitDirectAccessNumber(string $number): static
    {
        return $this->set('f_res_permit_inst_ar', $number);
    }

    /**
     * Get the residence permit expiry date (direct access).
     */
    public function getResPermitDirectAccessExpiry(): ?string
    {
        return $this->get('f_res_permit_inst_lixi');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setResPermitDirectAccessExpiry(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_res_permit_inst_lixi', $date);
    }

    // ==================== Residence Permits (Requires Approval) ====================

    /**
     * Whether employee has a residence permit requiring additional approval.
     */
    public function getResPermitApproval(): ?string
    {
        return $this->get('f_res_permit_ap');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setResPermitApproval(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_res_permit_ap', $value);
    }

    /**
     * Get the residence permit type (requires approval).
     */
    public function getResPermitApprovalType(): ?string
    {
        return $this->get('f_res_permit_ap_type');
    }

    /**
     * @param string $type Permit type code (max 5 digits)
     */
    public function setResPermitApprovalType(string $type): static
    {
        return $this->set('f_res_permit_ap_type', $type);
    }

    /**
     * Get the residence permit number (requires approval).
     */
    public function getResPermitApprovalNumber(): ?string
    {
        return $this->get('f_res_permit_ap_ar');
    }

    /**
     * @param string $number Permit number (max 20 chars)
     */
    public function setResPermitApprovalNumber(string $number): static
    {
        return $this->set('f_res_permit_ap_ar', $number);
    }

    /**
     * Get the residence permit expiry date (requires approval).
     */
    public function getResPermitApprovalExpiry(): ?string
    {
        return $this->get('f_res_permit_ap_lixi');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setResPermitApprovalExpiry(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_res_permit_ap_lixi', $date);
    }

    // ==================== Visa for Seasonal Work ====================

    /**
     * Whether employee is a third-country national with seasonal work visa.
     */
    public function getSeasonalWorkVisa(): ?string
    {
        return $this->get('f_res_permit_visa');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setSeasonalWorkVisa(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_res_permit_visa', $value);
    }

    /**
     * Get the entry visa number for seasonal work.
     */
    public function getSeasonalWorkVisaNumber(): ?string
    {
        return $this->get('f_res_permit_visa_ar');
    }

    /**
     * @param string $number Visa number (max 20 chars)
     */
    public function setSeasonalWorkVisaNumber(string $number): static
    {
        return $this->set('f_res_permit_visa_ar', $number);
    }

    /**
     * Get the entry visa validity start date.
     */
    public function getSeasonalWorkVisaFrom(): ?string
    {
        return $this->get('f_res_permit_visa_from');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setSeasonalWorkVisaFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_res_permit_visa_from', $date);
    }

    /**
     * Get the entry visa validity end date.
     */
    public function getSeasonalWorkVisaTo(): ?string
    {
        return $this->get('f_res_permit_visa_to');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setSeasonalWorkVisaTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_res_permit_visa_to', $date);
    }

    // ==================== Family Status ====================

    /**
     * Get the employee's marital status.
     */
    public function getMaritalStatus(): ?string
    {
        return $this->get('f_marital_status');
    }

    /**
     * @param MaritalStatus|string|int $status 0=Single, 1=Married, 2=Divorced, 3=Widowed
     */
    public function setMaritalStatus(MaritalStatus|string|int $status): static
    {
        if ($status instanceof MaritalStatus) {
            $status = $status->value;
        }

        return $this->set('f_marital_status', (string) $status);
    }

    /**
     * Get the number of children.
     */
    public function getNumberOfChildren(): ?string
    {
        return $this->get('f_arithmos_teknon');
    }

    /**
     * @param string|int $count Number of children (1-2 digits)
     */
    public function setNumberOfChildren(string|int $count): static
    {
        return $this->set('f_arithmos_teknon', (string) $count);
    }

    // ==================== Tax/Insurance IDs ====================

    /**
     * Get the employee's Tax Identification Number (TIN/AFM).
     */
    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    /**
     * @param string $afm 9-digit AFM
     */
    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    /**
     * Get the employee's Tax Office (DOY) code.
     */
    public function getTaxOffice(): ?string
    {
        return $this->get('f_doy');
    }

    /**
     * @param string $code 4-digit DOY code
     */
    public function setTaxOffice(string $code): static
    {
        return $this->set('f_doy', $code);
    }

    /**
     * Get the employee's IKA insurance registry number (AMIKA).
     */
    public function getAmika(): ?string
    {
        return $this->get('f_amika');
    }

    /**
     * @param string $amika AMIKA number (max 20 digits)
     */
    public function setAmika(string $amika): static
    {
        return $this->set('f_amika', $amika);
    }

    /**
     * Get the employee's Social Security Number (AMKA).
     */
    public function getAmka(): ?string
    {
        return $this->get('f_amka');
    }

    /**
     * @param string $amka AMKA number (max 20 digits)
     */
    public function setAmka(string $amka): static
    {
        return $this->set('f_amka', $amka);
    }

    /**
     * Get the employee's unemployment card number.
     */
    public function getUnemploymentCardNumber(): ?string
    {
        return $this->get('f_code_anergias');
    }

    /**
     * @param string $number Unemployment card number (max 20 chars)
     */
    public function setUnemploymentCardNumber(string $number): static
    {
        return $this->set('f_code_anergias', $number);
    }

    /**
     * Get the minor worker's booklet number.
     */
    public function getMinorWorkBookNumber(): ?string
    {
        return $this->get('f_ar_vivliou_anilikou');
    }

    /**
     * @param string $number Booklet number (max 20 chars)
     */
    public function setMinorWorkBookNumber(string $number): static
    {
        return $this->set('f_ar_vivliou_anilikou', $number);
    }

    // ==================== Education ====================

    /**
     * Get the employee's education level code.
     */
    public function getEducationLevel(): ?string
    {
        return $this->get('f_epipedo_morfosis');
    }

    /**
     * @param string $level Education level code (1-10 digits)
     */
    public function setEducationLevel(string $level): static
    {
        return $this->set('f_epipedo_morfosis', $level);
    }

    // ==================== Comments and Files ====================

    /**
     * Get general comments/remarks.
     */
    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    /**
     * @param string $comments Comments text (max 100 chars)
     */
    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * Get the third-country national work authorization documents file.
     */
    public function getForeignWorkerFile(): ?string
    {
        return $this->get('f_foreign_file');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setForeignWorkerFile(string $base64): static
    {
        return $this->set('f_foreign_file', $base64);
    }

    /**
     * Get the minor worker's booklet file.
     */
    public function getMinorWorkerFile(): ?string
    {
        return $this->get('f_young_file');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setMinorWorkerFile(string $base64): static
    {
        return $this->set('f_young_file', $base64);
    }
}
