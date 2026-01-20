<?php

namespace OxygenSuite\OxygenErgani\Models\Modification;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\BasicsAcceptance;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\SpecialCase;
use OxygenSuite\OxygenErgani\Factories\Modification\ModificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;

/**
 * Employment modification declaration model for MA_v1 schema (WebMA).
 *
 * Used for reporting modifications to employment terms for regular employees.
 *
 * @see xsd/MA_v1.xsd
 *
 * @method static ModificationDeclarationFactory factory(int $count = 1)
 */
class ModificationDeclaration extends Declaration
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected array $defaults = [
        'ModificationTypeSelections' => [],
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

        // Change Details (MA specific)
        'f_date_metabolhs',
        'f_eidos_dieuthethshs',
        'f_eidos_dieuthethshs_comments',
        'f_periodos_anaforas_from',
        'f_periodos_anaforas_to',

        // Specialty/Employment
        'f_eidikothta',
        'f_eidikothta_anal',
        'f_proipiresia',
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_xronos_katabolhs',

        // Work Location
        'f_topos_ergasias',
        'f_topos_ergasias_comments',

        // Employment Type
        'f_sxeshapasxolisis',
        'f_orismenou_apo',
        'f_orismenou_ews',

        // Employment Status
        'f_kathestosapasxolisis',
        'f_xaraktirismos',
        'f_special_case',
        'f_responsible_position',

        // Collective Agreement
        'f_efarmostea_sillogiki_simbasi',
        'f_efarmostea_sillogiki_simbasi_comments',

        // Insurance
        'f_kyria_asfalish',
        'f_prosthetes_asfalistikes_paroxes',
        'f_ipoxreotiki_katartisi',

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

        // DYPA/OAED Programs
        'f_topothetisioaed',
        'f_programaoaed',

        // Trial Period
        'f_trial_period',
        'f_trial_date_to',

        // Borrow Details (Optional for MA)
        'f_borrow_type',
        'f_borrow_date_from',
        'f_borrow_date_to',
        'f_borrow_company_afm',
        'f_borrow_company_eponimia',

        // Acceptance and Files
        'f_basics_acceptance',
        'f_file',
        'f_comments',
        'f_foreign_file',
        'f_young_file',
        'f_epibolh_file',

        // Nested Arrays
        'ModificationTypeSelections',
        'SupplementaryInsuranceSelections',
    ];

    // ==================== Settlement/Reference Period (MA specific) ====================

    /**
     * Get the settlement type.
     */
    public function getSettlementType(): ?string
    {
        return $this->get('f_eidos_dieuthethshs');
    }

    /**
     * @param SettlementType|string|int $type 0=Collective, 1=Individual, 2=No settlement (or use SettlementType enum)
     */
    public function setSettlementType(SettlementType|string|int $type): static
    {
        if ($type instanceof SettlementType) {
            $type = $type->value;
        }

        return $this->set('f_eidos_dieuthethshs', (string) $type);
    }

    /**
     * Get the settlement type comments.
     */
    public function getSettlementTypeComment(): ?string
    {
        return $this->get('f_eidos_dieuthethshs_comments');
    }

    /**
     * @param string $comment Settlement type comments (max 200 chars)
     */
    public function setSettlementTypeComment(string $comment): static
    {
        return $this->set('f_eidos_dieuthethshs_comments', $comment);
    }

    /**
     * Get the reference period start date.
     */
    public function getReferencePeriodFrom(): ?string
    {
        return $this->get('f_periodos_anaforas_from');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setReferencePeriodFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_periodos_anaforas_from', $date);
    }

    /**
     * Get the reference period end date.
     */
    public function getReferencePeriodTo(): ?string
    {
        return $this->get('f_periodos_anaforas_to');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setReferencePeriodTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_periodos_anaforas_to', $date);
    }

    // ==================== Salary/Experience (MA specific) ====================

    /**
     * Get years of prior experience.
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
     * Get the employee's gross salary.
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

    // ==================== Employment Type (MA specific) ====================

    /**
     * Get the employment type (indefinite, fixed-term, or borrowed).
     */
    public function getEmploymentType(): ?string
    {
        return $this->get('f_sxeshapasxolisis');
    }

    /**
     * @param EmploymentType|string|int $type 0=Indefinite, 1=Fixed-term, 2=Project, 3=Borrowed (or use EmploymentType enum)
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
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setFixedTermFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

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
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setFixedTermTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_ews', $date);
    }

    /**
     * Get the special case classification.
     */
    public function getSpecialCase(): ?string
    {
        return $this->get('f_special_case');
    }

    /**
     * @param SpecialCase|string|int $case 2=Private law narrow public, 3=Private law wider public (or use SpecialCase enum)
     */
    public function setSpecialCase(SpecialCase|string|int $case): static
    {
        if ($case instanceof SpecialCase) {
            $case = $case->value;
        }

        return $this->set('f_special_case', (string) $case);
    }

    // ==================== Collective Agreement (MA specific) ====================

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

    // ==================== Insurance (MA specific) ====================

    /**
     * Get the primary insurance code.
     */
    public function getPrimaryInsurance(): ?string
    {
        return $this->get('f_kyria_asfalish');
    }

    /**
     * @param string $code Primary insurance code (1-6 digits)
     */
    public function setPrimaryInsurance(string $code): static
    {
        return $this->set('f_kyria_asfalish', $code);
    }

    /**
     * Get additional insurance benefits description.
     */
    public function getAdditionalInsuranceBenefits(): ?string
    {
        return $this->get('f_prosthetes_asfalistikes_paroxes');
    }

    /**
     * @param string $benefits Benefits description (max 200 chars)
     */
    public function setAdditionalInsuranceBenefits(string $benefits): static
    {
        return $this->set('f_prosthetes_asfalistikes_paroxes', $benefits);
    }

    /**
     * Whether mandatory training applies.
     */
    public function getMandatoryTraining(): ?string
    {
        return $this->get('f_ipoxreotiki_katartisi');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setMandatoryTraining(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_ipoxreotiki_katartisi', $value);
    }

    // ==================== DYPA Programs (MA specific) ====================

    /**
     * Whether placement via DYPA employment program.
     */
    public function getDypaPlacement(): ?string
    {
        return $this->get('f_topothetisioaed');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setDypaPlacement(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_topothetisioaed', $value);
    }

    /**
     * Get the DYPA program code.
     */
    public function getDypaProgram(): ?string
    {
        return $this->get('f_programaoaed');
    }

    /**
     * @param string $code DYPA program code (max 10 chars)
     */
    public function setDypaProgram(string $code): static
    {
        return $this->set('f_programaoaed', $code);
    }

    // ==================== Trial Period (MA specific) ====================

    /**
     * Whether this is a trial period.
     */
    public function getTrialPeriod(): ?string
    {
        return $this->get('f_trial_period');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setTrialPeriod(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_trial_period', $value);
    }

    /**
     * Get the trial period end date.
     */
    public function getTrialPeriodEndDate(): ?string
    {
        return $this->get('f_trial_date_to');
    }

    /**
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setTrialPeriodEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_trial_date_to', $date);
    }

    // ==================== Acceptance Files (MA specific) ====================

    /**
     * Get the essential terms acceptance method.
     */
    public function getBasicsAcceptance(): ?string
    {
        return $this->get('f_basics_acceptance');
    }

    /**
     * @param BasicsAcceptance|string|int $acceptance 0=With file, 1=Await MyErgani, 2=Not required (or use BasicsAcceptance enum)
     */
    public function setBasicsAcceptance(BasicsAcceptance|string|int $acceptance): static
    {
        if ($acceptance instanceof BasicsAcceptance) {
            $acceptance = $acceptance->value;
        }

        return $this->set('f_basics_acceptance', (string) $acceptance);
    }

    /**
     * Get the essential terms acceptance file.
     */
    public function getAcceptanceFile(): ?string
    {
        return $this->get('f_file');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setAcceptanceFile(string $base64): static
    {
        return $this->set('f_file', $base64);
    }

    /**
     * Get the rotation system decision file.
     */
    public function getRotationDecisionFile(): ?string
    {
        return $this->get('f_epibolh_file');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setRotationDecisionFile(string $base64): static
    {
        return $this->set('f_epibolh_file', $base64);
    }

    // ==================== Modification Types (MA specific) ====================

    /**
     * Get the modification type selections.
     *
     * @return ModificationTypeSelection[]|array<array<string, mixed>>
     */
    public function getModificationTypeSelections(): array
    {
        return $this->get('ModificationTypeSelections') ?? [];
    }

    /**
     * Set the modification type selections.
     *
     * @param ModificationTypeSelection[]|array<array<string, mixed>> $selections
     */
    public function setModificationTypeSelections(array $selections): static
    {
        return $this->set('ModificationTypeSelections', $selections);
    }

    /**
     * Add a modification type selection.
     */
    public function addModificationTypeSelection(ModificationTypeSelection $selection): static
    {
        $selections = $this->getModificationTypeSelections();
        $selections[] = $selection;

        return $this->setModificationTypeSelections($selections);
    }

    // ==================== Supplementary Insurance (MA specific) ====================

    /**
     * Get the supplementary insurance selections.
     *
     * @return SupplementaryInsuranceSelection[]|array<array<string, mixed>>
     */
    public function getSupplementaryInsuranceSelections(): array
    {
        return $this->get('SupplementaryInsuranceSelections') ?? [];
    }

    /**
     * Set the supplementary insurance selections.
     *
     * @param SupplementaryInsuranceSelection[]|array<array<string, mixed>> $selections
     */
    public function setSupplementaryInsuranceSelections(array $selections): static
    {
        return $this->set('SupplementaryInsuranceSelections', $selections);
    }

    /**
     * Add a supplementary insurance selection.
     */
    public function addSupplementaryInsuranceSelection(SupplementaryInsuranceSelection $selection): static
    {
        $selections = $this->getSupplementaryInsuranceSelections();
        $selections[] = $selection;

        return $this->setSupplementaryInsuranceSelections($selections);
    }
}
