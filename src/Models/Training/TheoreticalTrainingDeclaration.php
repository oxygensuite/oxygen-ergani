<?php

namespace OxygenSuite\OxygenErgani\Models\Training;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiary;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramProvider;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramSignOff;

/**
 * Theoretical training start declaration model for E32_v1 schema.
 *
 * Announces the start of theoretical training for a program beneficiary (E3.2),
 * optionally followed by practical training at a business.
 *
 * @see xsd/E32_v1.xsd
 */
class TheoreticalTrainingDeclaration extends Model
{
    use HasFactory;
    use HasBeneficiary;
    use HasProgramProvider;
    use HasProgramSignOff;

    /** @var array<string, string> */
    protected array $casts = [
        'f_week_hours' => 'greek_float:1',
        'f_daily_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_ypiresia_oaed',
        'f_ypiresia_sepe',
        'f_programma_voucher_code',
        'f_programma_voucher',
        'f_mer_organization',
        'f_mer_stakod1',
        'f_mer_stakod2',
        'f_mer_stakod3',
        'f_mer_stakod4',
        'f_mer_stakod5',
        'f_mpa_stakod',
        'f_mpa_kallikratis',
        'f_eopep_code',
        'f_edu_agency_category',
        'f_mea_eponimo',
        'f_mea_onoma',
        'f_mea_eponimo_patera',
        'f_mea_onoma_patera',
        'f_mea_eponimo_miteras',
        'f_mea_onoma_miteras',
        'f_mea_sex',
        'f_mea_birth_city',
        'f_mea_date_birth',
        'f_mea_nationality',
        'f_mea_typos_taytotitas',
        'f_mea_ar_taytotitas',
        'f_mea_ekdousa',
        'f_mea_date_ekdosis',
        'f_mea_date_ekdosis_lixi',
        'f_mea_res_permit',
        'f_mea_res_permit_type',
        'f_mea_res_permit_ar',
        'f_mea_res_permit_lixi',
        'f_mea_marital_status',
        'f_mea_ar_teknon',
        'f_mea_afm',
        'f_mea_doy',
        'f_mea_insurance_agency',
        'f_mea_am_ika',
        'f_mea_amka',
        'f_mea_code_anergias',
        'f_mea_ar_vivliou_anilikou',
        'f_mea_dieythinsi',
        'f_mea_kallikratis',
        'f_mea_tk',
        'f_mea_til',
        'f_mea_fax',
        'f_mea_email',
        'f_mea_epipedo_morfosis',
        'f_mea_practical_education',
        'f_mea_voucher',
        'f_mea_kayas',
        'f_date_proslipsis',
        'f_week_hours',
        'f_orario',
        'f_orario_dialeima',
        'f_total_hours_theory',
        'f_total_hours_practice',
        'f_total_insurance_days',
        'f_daily_apodoxes',
        'f_theory_education',
        'f_theory_description',
        'f_theory_code',
        'f_orismenou_ews',
        'f_benefiter_status_after',
        'f_months_unempoyment',
        'f_benefiter_status_before',
        'f_comments',
        'f_practice_mer_eponimia',
        'f_practice_mer_afm',
        'f_practice_mer_doy',
        'f_practice_mer_ame',
        'f_practice_mer_no_ame',
        'f_practice_mpa_address',
        'f_practice_mpa_kallikratis',
        'f_practice_mpa_tk',
        'f_practice_mpa_til',
        'f_practice_mpa_fax',
        'f_practice_mpa_email',
        'f_practice_date_from',
        'f_practice_date_to',
        'f_mek_afm',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    // ==================== Program ====================

    public function getProgramCode(): ?string
    {
        return $this->get('f_programma_voucher_code');
    }

    public function setProgramCode(string $value): static
    {
        return $this->set('f_programma_voucher_code', $value);
    }

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

    public function getEopepCode(): ?string
    {
        return $this->get('f_eopep_code');
    }

    /**
     * Training provider code (EOPPEP).
     */
    public function setEopepCode(string $value): static
    {
        return $this->set('f_eopep_code', $value);
    }

    public function getTrainingProviderCategory(): ?string
    {
        return $this->get('f_edu_agency_category');
    }

    /**
     * Training provider category code (3 digits).
     */
    public function setTrainingProviderCategory(string $value): static
    {
        return $this->set('f_edu_agency_category', $value);
    }

    // ==================== Beneficiary ====================

    public function getMinorBookNumber(): ?string
    {
        return $this->get('f_mea_ar_vivliou_anilikou');
    }

    public function setMinorBookNumber(string $value): static
    {
        return $this->set('f_mea_ar_vivliou_anilikou', $value);
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

    // ==================== Theoretical Training ====================

    public function getTrainingStartDate(): ?string
    {
        return $this->get('f_date_proslipsis');
    }

    public function setTrainingStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_proslipsis', $date);
    }

    public function getWeeklyHours(): ?float
    {
        return $this->greekFloat('f_week_hours');
    }

    /**
     * Weekly theoretical training hours.
     */
    public function setWeeklyHours(float $amount): static
    {
        return $this->set('f_week_hours', $amount);
    }

    public function getTheoryHours(): ?string
    {
        return $this->get('f_total_hours_theory');
    }

    /**
     * Total theoretical training hours in the program.
     */
    public function setTheoryHours(int|string $value): static
    {
        return $this->set('f_total_hours_theory', (string) $value);
    }

    public function getPracticeHours(): ?string
    {
        return $this->get('f_total_hours_practice');
    }

    /**
     * Total practical training hours in the program.
     */
    public function setPracticeHours(int|string $value): static
    {
        return $this->set('f_total_hours_practice', (string) $value);
    }

    public function getInsuranceDays(): ?string
    {
        return $this->get('f_total_insurance_days');
    }

    /**
     * Insurance days for theoretical and practical training.
     */
    public function setInsuranceDays(int|string $value): static
    {
        return $this->set('f_total_insurance_days', (string) $value);
    }

    public function getDailyAllowance(): ?float
    {
        return $this->greekFloat('f_daily_apodoxes');
    }

    /**
     * Gross daily training allowance.
     */
    public function setDailyAllowance(float $amount): static
    {
        return $this->set('f_daily_apodoxes', $amount);
    }

    public function getTheoryProgram(): ?string
    {
        return $this->get('f_theory_education');
    }

    /**
     * Theoretical training program code.
     */
    public function setTheoryProgram(string $value): static
    {
        return $this->set('f_theory_education', $value);
    }

    public function getTheoryDescription(): ?string
    {
        return $this->get('f_theory_description');
    }

    public function setTheoryDescription(string $value): static
    {
        return $this->set('f_theory_description', $value);
    }

    public function getTheoryCode(): ?string
    {
        return $this->get('f_theory_code');
    }

    public function setTheoryCode(string $value): static
    {
        return $this->set('f_theory_code', $value);
    }

    public function getTrainingEndDate(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    public function setTrainingEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_ews', $date);
    }

    public function getStatusAtApplication(): ?string
    {
        return $this->get('f_benefiter_status_after');
    }

    /**
     * Beneficiary status when applying to the program (3-digit code).
     */
    public function setStatusAtApplication(string $value): static
    {
        return $this->set('f_benefiter_status_after', $value);
    }

    public function getUnemploymentMonths(): ?string
    {
        return $this->get('f_months_unempoyment');
    }

    public function setUnemploymentMonths(int|string $value): static
    {
        return $this->set('f_months_unempoyment', (string) $value);
    }

    public function getStatusBeforeApplication(): ?string
    {
        return $this->get('f_benefiter_status_before');
    }

    /**
     * Beneficiary status one month before applying (3-digit code).
     */
    public function setStatusBeforeApplication(string $value): static
    {
        return $this->set('f_benefiter_status_before', $value);
    }

    // ==================== Practical Training Business ====================

    public function getPracticeCompanyName(): ?string
    {
        return $this->get('f_practice_mer_eponimia');
    }

    public function setPracticeCompanyName(string $value): static
    {
        return $this->set('f_practice_mer_eponimia', $value);
    }

    public function getPracticeCompanyAfm(): ?string
    {
        return $this->get('f_practice_mer_afm');
    }

    public function setPracticeCompanyAfm(string $value): static
    {
        return $this->set('f_practice_mer_afm', $value);
    }

    public function getPracticeCompanyTaxOffice(): ?string
    {
        return $this->get('f_practice_mer_doy');
    }

    public function setPracticeCompanyTaxOffice(string $value): static
    {
        return $this->set('f_practice_mer_doy', $value);
    }

    public function getPracticeCompanyAme(): ?string
    {
        return $this->get('f_practice_mer_ame');
    }

    /**
     * Employer registry number (AME) of the business.
     */
    public function setPracticeCompanyAme(string $value): static
    {
        return $this->set('f_practice_mer_ame', $value);
    }

    public function getPracticeCompanyWithoutAme(): ?string
    {
        return $this->get('f_practice_mer_no_ame');
    }

    /**
     * Whether the business has no AME.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setPracticeCompanyWithoutAme(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_practice_mer_no_ame', $value);
    }

    public function getPracticeBranchAddress(): ?string
    {
        return $this->get('f_practice_mpa_address');
    }

    public function setPracticeBranchAddress(string $value): static
    {
        return $this->set('f_practice_mpa_address', $value);
    }

    public function getPracticeBranchMunicipalityCode(): ?string
    {
        return $this->get('f_practice_mpa_kallikratis');
    }

    public function setPracticeBranchMunicipalityCode(string $value): static
    {
        return $this->set('f_practice_mpa_kallikratis', $value);
    }

    public function getPracticeBranchPostalCode(): ?string
    {
        return $this->get('f_practice_mpa_tk');
    }

    public function setPracticeBranchPostalCode(string $value): static
    {
        return $this->set('f_practice_mpa_tk', $value);
    }

    public function getPracticeBranchPhone(): ?string
    {
        return $this->get('f_practice_mpa_til');
    }

    public function setPracticeBranchPhone(string $value): static
    {
        return $this->set('f_practice_mpa_til', $value);
    }

    public function getPracticeBranchFax(): ?string
    {
        return $this->get('f_practice_mpa_fax');
    }

    public function setPracticeBranchFax(string $value): static
    {
        return $this->set('f_practice_mpa_fax', $value);
    }

    public function getPracticeBranchEmail(): ?string
    {
        return $this->get('f_practice_mpa_email');
    }

    public function setPracticeBranchEmail(string $value): static
    {
        return $this->set('f_practice_mpa_email', $value);
    }

    public function getPracticeDateFrom(): ?string
    {
        return $this->get('f_practice_date_from');
    }

    public function setPracticeDateFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_practice_date_from', $date);
    }

    public function getPracticeDateTo(): ?string
    {
        return $this->get('f_practice_date_to');
    }

    public function setPracticeDateTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_practice_date_to', $date);
    }

    // ==================== Attachments ====================

    public function getMinorFile(): ?string
    {
        return $this->get('f_young_file');
    }

    /**
     * Minor worker book (base64-encoded PDF).
     */
    public function setMinorFile(string $value): static
    {
        return $this->set('f_young_file', $value);
    }

    /**
     * Set the theoretical training period in one call.
     */
    public function setTrainingPeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setTrainingStartDate($from)->setTrainingEndDate($to);
    }

    /**
     * Set the practical training period in one call.
     */
    public function setPracticePeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setPracticeDateFrom($from)->setPracticeDateTo($to);
    }
}
