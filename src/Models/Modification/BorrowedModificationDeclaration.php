<?php

namespace OxygenSuite\OxygenErgani\Models\Modification;

use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Factories\Modification\BorrowedModificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;

/**
 * Borrowed employee modification declaration model for MAD_v1 schema (WebMAD).
 *
 * Used for reporting modifications to employment terms for loaned/borrowed employees.
 * The loan details (f_borrow_*) are required, unlike in MA where they're optional.
 *
 * @see xsd/MAD_v1.xsd
 *
 * @method static BorrowedModificationDeclarationFactory factory(int $count = 1)
 */
class BorrowedModificationDeclaration extends Declaration
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

        // Change Date (MAD specific starts here)
        'f_date_metabolhs',

        // Borrow Details (REQUIRED for MAD)
        'f_borrow_type',
        'f_borrow_date_from',
        'f_borrow_date_to',
        'f_borrow_company_afm',
        'f_borrow_company_eponimia',

        // Specialty
        'f_eidikothta',
        'f_eidikothta_anal',

        // Salary Payment Source (MAD-only, REQUIRED)
        'f_kataboli_apodoxon',

        // Salary (Optional for MAD)
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_xronos_katabolhs',

        // Collective Agreement
        'f_efarmostea_sillogiki_simbasi',
        'f_efarmostea_sillogiki_simbasi_comments',

        // Work Location
        'f_topos_ergasias',
        'f_topos_ergasias_comments',

        // Employment Status
        'f_kathestosapasxolisis',
        'f_xaraktirismos',
        'f_responsible_position',

        // Work Organization (Digital)
        'f_working_time_digital_organization',
        'f_mh_problepsimo_programma',
        'f_paraggelia_hmeres_hours',
        'f_paraggelia_min_notification',
        'f_paraggelia_notes',
        'f_week_hours',
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
    ];

    // ==================== Salary Payment Source (MAD-only) ====================

    /**
     * Get the salary payment source.
     */
    public function getSalaryPaymentSource(): ?string
    {
        return $this->get('f_kataboli_apodoxon');
    }

    /**
     * @param SalaryPaymentSource|string|int $source 0=Direct employer/EPA, 1=Indirect employer (or use SalaryPaymentSource enum)
     */
    public function setSalaryPaymentSource(SalaryPaymentSource|string|int $source): static
    {
        if ($source instanceof SalaryPaymentSource) {
            $source = $source->value;
        }

        return $this->set('f_kataboli_apodoxon', (string) $source);
    }

    // ==================== Salary (Optional for MAD) ====================

    /**
     * Get the employee's gross salary.
     *
     * Only required when salary is paid by indirect employer.
     */
    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    /**
     * @param float $amount Gross salary amount
     */
    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    /**
     * Get the employee's hourly wage.
     *
     * Only required when salary is paid by indirect employer.
     */
    public function getHourlyWage(): ?float
    {
        return $this->greekFloat('f_hour_apodoxes');
    }

    /**
     * @param float $amount Hourly wage amount
     */
    public function setHourlyWage(float $amount): static
    {
        return $this->set('f_hour_apodoxes', $amount);
    }

    /**
     * Get the salary payment timing description.
     */
    public function getSalaryPaymentTiming(): ?string
    {
        return $this->get('f_xronos_katabolhs');
    }

    /**
     * @param string $timing Payment timing description (max 200 chars)
     */
    public function setSalaryPaymentTiming(string $timing): static
    {
        return $this->set('f_xronos_katabolhs', $timing);
    }

    // ==================== Collective Agreement ====================

    /**
     * Whether a collective agreement applies.
     */
    public function getCollectiveAgreementApplies(): ?string
    {
        return $this->get('f_efarmostea_sillogiki_simbasi');
    }

    /**
     * @param string|bool $value 0=No (minimum legal wages), 1=Yes (or boolean)
     */
    public function setCollectiveAgreementApplies(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_efarmostea_sillogiki_simbasi', $value);
    }

    /**
     * Get the collective agreement comments.
     */
    public function getCollectiveAgreementComment(): ?string
    {
        return $this->get('f_efarmostea_sillogiki_simbasi_comments');
    }

    /**
     * @param string $comment Collective agreement details (max 200 chars)
     */
    public function setCollectiveAgreementComment(string $comment): static
    {
        return $this->set('f_efarmostea_sillogiki_simbasi_comments', $comment);
    }
}
