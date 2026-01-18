<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring;

use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;

/**
 * Hiring deletion/borrowing declaration model for E3D_v1 schema.
 *
 * Used for reporting employee lending FROM the direct employer to an indirect employer.
 * This is submitted by the direct employer (lender) when lending an employee.
 *
 * @see xsd/E3D_v1.xsd
 */
class DeletionDeclaration extends Declaration
{
    use HasFactory;
    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
        'f_hour_apodoxes' => 'greek_float',
        'f_week_hours' => 'greek_float:1',
        'f_full_employment_hours' => 'greek_float:1',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        // Branch/Location
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_ypiresia_sepe',
        'f_ypiresia_oaed',
        'f_kad_pararthmatos',
        'f_kallikratis_pararthmatos',

        // Personal Information
        'f_eponymo',
        'f_onoma',
        'f_onoma_patros',
        'f_onoma_mitros',
        'f_birthdate',
        'f_sex',

        // Identity/Nationality
        'f_yphkoothta',
        'f_typos_taytothtas',
        'f_ar_taytothtas',
        'f_ekdousa_arxh',
        'f_date_ekdosis',
        'f_date_ekdosis_lixi',

        // Residence Permits (Direct Access)
        'f_res_permit_inst',
        'f_res_permit_inst_type',
        'f_res_permit_inst_ar',
        'f_res_permit_inst_lixi',

        // Residence Permits (Requires Approval)
        'f_res_permit_ap',
        'f_res_permit_ap_type',
        'f_res_permit_ap_ar',
        'f_res_permit_ap_lixi',

        // Visa for Seasonal Work
        'f_res_permit_visa',
        'f_res_permit_visa_ar',
        'f_res_permit_visa_from',
        'f_res_permit_visa_to',

        // Family Status
        'f_marital_status',
        'f_arithmos_teknon',

        // Tax/Insurance IDs
        'f_afm',
        'f_doy',
        'f_amika',
        'f_amka',
        'f_code_anergias',
        'f_ar_vivliou_anilikou',

        // Education
        'f_epipedo_morfosis',

        // Borrowing Fields (E3D specific)
        'f_borrow_type',
        'f_borrow_date_from',
        'f_borrow_date_to',
        'f_borrow_company_afm',
        'f_borrow_company_eponimia',

        // Employment Details
        'f_proslipsitime',
        'f_apoxwrisitime',
        'f_week_hours',
        'f_eidikothta',
        'f_eidikothta_anal',
        'f_kathestosapasxolisis',
        'f_xaraktirismos',
        'f_responsible_position',

        // Work Organization (Digital)
        'f_working_time_digital_organization',
        'f_full_employment_hours',
        'f_week_days',
        'f_euelikto_wrario_minutes',
        'f_working_card',
        'f_dialeimma_minutes',
        'f_dialeimma_entos_wrariou',

        // Files
        'f_comments',
        'f_foreign_file',
        'f_young_file',

        // Unpredictable Work Schedule
        'f_mh_provlepsimo_programma',
        'f_paraggelia_hmeres_hours',
        'f_paraggelia_min_notification',
        'f_paraggelia_notes',

        // Work Location
        'f_topos_ergasias',
        'f_topos_ergasias_comment',

        // Wage Payment (E3D specific order)
        'f_kataboli_apodoxon',
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_xronos_katavolis_apodoxon',
        'f_efarmoste_sillogiki_simbasi',
        'f_efarmoste_sillogiki_simbasi_comments',

        // Employee Agreement
        'f_ergazom_borrow_agreement',
    ];

    // ==================== Borrowing Fields (E3D specific) ====================

    /**
     * Get the borrowing type.
     */
    public function getBorrowType(): ?string
    {
        return $this->get('f_borrow_type');
    }

    /**
     * @param LoanType|string|int $type 0=Genuine borrowing, 1=EPA (Temporary Employment Agency) (or use LoanType enum)
     */
    public function setBorrowType(LoanType|string|int $type): static
    {
        if ($type instanceof LoanType) {
            $type = $type->value;
        }

        return $this->set('f_borrow_type', (string) $type);
    }

    /**
     * Get the employee lending start date.
     */
    public function getBorrowDateFrom(): ?string
    {
        return $this->get('f_borrow_date_from');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setBorrowDateFrom(string $date): static
    {
        return $this->set('f_borrow_date_from', $date);
    }

    /**
     * Get the employee lending end date.
     */
    public function getBorrowDateTo(): ?string
    {
        return $this->get('f_borrow_date_to');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setBorrowDateTo(string $date): static
    {
        return $this->set('f_borrow_date_to', $date);
    }

    /**
     * Get the indirect employer's TIN (AFM).
     */
    public function getBorrowCompanyAfm(): ?string
    {
        return $this->get('f_borrow_company_afm');
    }

    /**
     * @param string $afm 9-digit AFM of the indirect employer
     */
    public function setBorrowCompanyAfm(string $afm): static
    {
        return $this->set('f_borrow_company_afm', $afm);
    }

    /**
     * Get the indirect employer's company name.
     */
    public function getBorrowCompanyName(): ?string
    {
        return $this->get('f_borrow_company_eponimia');
    }

    /**
     * @param string $name Company name (max 230 chars)
     */
    public function setBorrowCompanyName(string $name): static
    {
        return $this->set('f_borrow_company_eponimia', $name);
    }

    // ==================== Wage Payment (E3D specific) ====================

    /**
     * Get who pays wages in the borrowing arrangement.
     */
    public function getWagePaymentBy(): ?string
    {
        return $this->get('f_kataboli_apodoxon');
    }

    /**
     * @param SalaryPaymentSource|string|int $by 0=Direct employer/EPA, 1=Indirect employer (or use SalaryPaymentSource enum)
     */
    public function setWagePaymentBy(SalaryPaymentSource|string|int $by): static
    {
        if ($by instanceof SalaryPaymentSource) {
            $by = $by->value;
        }

        return $this->set('f_kataboli_apodoxon', (string) $by);
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
     * Get the wage payment schedule/timing.
     */
    public function getWagePaymentTime(): ?string
    {
        return $this->get('f_xronos_katavolis_apodoxon');
    }

    /**
     * @param string $time Payment schedule description (max 100 chars)
     */
    public function setWagePaymentTime(string $time): static
    {
        return $this->set('f_xronos_katavolis_apodoxon', $time);
    }

    /**
     * Whether a collective bargaining agreement applies.
     */
    public function getCollectiveAgreementApplicable(): ?string
    {
        return $this->get('f_efarmoste_sillogiki_simbasi');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setCollectiveAgreementApplicable(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_efarmoste_sillogiki_simbasi', $value);
    }

    /**
     * Get remarks about the applicable collective bargaining agreement.
     */
    public function getCollectiveAgreementComments(): ?string
    {
        return $this->get('f_efarmoste_sillogiki_simbasi_comments');
    }

    /**
     * @param string $comments Agreement details/comments (max 500 chars)
     */
    public function setCollectiveAgreementComments(string $comments): static
    {
        return $this->set('f_efarmoste_sillogiki_simbasi_comments', $comments);
    }

    // ==================== Employee Agreement ====================

    /**
     * Whether employee has agreed to the borrowing arrangement.
     */
    public function getEmployeeBorrowAgreement(): ?string
    {
        return $this->get('f_ergazom_borrow_agreement');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setEmployeeBorrowAgreement(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_ergazom_borrow_agreement', $value);
    }
}
