<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasAcceptanceFiles;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasDypaPrograms;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasExtendedEmploymentDetails;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasInsurance;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasTrialPeriod;
use OxygenSuite\OxygenErgani\Models\Hiring\Concerns\HasWagePayment;

/**
 * Hiring declaration with lending model for E3PD_v1 schema.
 *
 * Used for reporting employee hirings TO the indirect employer (borrower).
 * This is submitted by the indirect employer when receiving a borrowed employee.
 *
 * @see xsd/E3PD_v1.xsd
 */
class LendingDeclaration extends Declaration
{
    use HasFactory;
    use HasExtendedEmploymentDetails;
    use HasDypaPrograms;
    use HasTrialPeriod;
    use HasInsurance;
    use HasWagePayment;
    use HasAcceptanceFiles;

    /** @var array<string, mixed> */
    protected array $defaults = [
        'SupplementaryInsuranceSelections' => [],
    ];

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

        // Employment Details
        'f_proslipsidate',
        'f_proslipsitime',
        'f_apoxwrisitime',
        'f_week_hours',
        'f_eidikothta',
        'f_eidikothta_anal',
        'f_proipiresia',
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_sxeshapasxolisis',
        'f_orismenou_apo',
        'f_orismenou_ews',
        'f_kathestosapasxolisis',
        'f_xaraktirismos',
        'f_special_case',
        'f_responsible_position',

        // Work Organization (Digital)
        'f_working_time_digital_organization',
        'f_full_employment_hours',
        'f_week_days',
        'f_euelikto_wrario_minutes',
        'f_working_card',
        'f_dialeimma_minutes',
        'f_dialeimma_entos_wrariou',

        // DYPA/OAED Programs
        'f_topothetisioaed',
        'f_programaoaed',
        'f_replaceprograma',
        'f_replaceprograma_afm',
        'f_replaceprograma_amka',

        // Trial Period
        'f_trial_period',
        'f_trial_date_to',

        // Lending Fields (E3PD specific)
        'f_borrow_date_from',
        'f_borrow_date_to',
        'f_borrow_company_afm',
        'f_borrow_company_eponimia',

        // Acceptance and Files
        'f_basics_acceptance',
        'f_file',
        'f_atomikh_symbash',
        'f_file_symbash',
        'f_comments',
        'f_foreign_file',
        'f_young_file',

        // Wage Payment
        'f_xronos_katavolis_apodoxon',
        'f_ipoxreotiki_katartisi',
        'f_efarmoste_sillogiki_simbasi',
        'f_efarmoste_sillogiki_simbasi_comments',

        // Insurance
        'f_kyria_asfalisi',
        'SupplementaryInsuranceSelections',
        'f_prosthetes_asfalistikes',

        // Unpredictable Work Schedule
        'f_mh_provlepsimo_programma',
        'f_paraggelia_hmeres_hours',
        'f_paraggelia_min_notification',
        'f_paraggelia_notes',

        // Work Location
        'f_topos_ergasias',
        'f_topos_ergasias_comment',
    ];

    // ==================== Lending Fields (E3PD specific) ====================

    /**
     * Get the employee lending start date.
     */
    public function getLendingDateFrom(): ?string
    {
        return $this->get('f_borrow_date_from');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setLendingDateFrom(string $date): static
    {
        return $this->set('f_borrow_date_from', $date);
    }

    /**
     * Get the employee lending end date.
     */
    public function getLendingDateTo(): ?string
    {
        return $this->get('f_borrow_date_to');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setLendingDateTo(string $date): static
    {
        return $this->set('f_borrow_date_to', $date);
    }

    /**
     * Get the direct employer's TIN (AFM).
     *
     * This is the AFM of the company that is lending the employee.
     */
    public function getDirectEmployerAfm(): ?string
    {
        return $this->get('f_borrow_company_afm');
    }

    /**
     * @param string $afm 9-digit AFM of the direct employer (lender)
     */
    public function setDirectEmployerAfm(string $afm): static
    {
        return $this->set('f_borrow_company_afm', $afm);
    }

    /**
     * Get the direct employer's company name.
     *
     * This is the name of the company that is lending the employee.
     */
    public function getDirectEmployerName(): ?string
    {
        return $this->get('f_borrow_company_eponimia');
    }

    /**
     * @param string $name Company name (max 230 chars)
     */
    public function setDirectEmployerName(string $name): static
    {
        return $this->set('f_borrow_company_eponimia', $name);
    }
}
