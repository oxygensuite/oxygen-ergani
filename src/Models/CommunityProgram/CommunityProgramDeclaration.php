<?php

namespace OxygenSuite\OxygenErgani\Models\CommunityProgram;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiary;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramProvider;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramSignOff;

/**
 * Community-benefit program employment declaration model for E31_v1 schema.
 *
 * Announces the employment of a beneficiary of a community-benefit program (E3.1).
 *
 * @see xsd/E31_v1.xsd
 */
class CommunityProgramDeclaration extends Model
{
    use HasFactory;
    use HasBeneficiary;
    use HasProgramProvider;
    use HasProgramSignOff;

    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
        'f_hour_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_ypiresia_oaed',
        'f_ypiresia_sepe',
        'f_programma_benefit',
        'f_mer_organization',
        'f_mer_stakod1',
        'f_mer_stakod2',
        'f_mer_stakod3',
        'f_mer_stakod4',
        'f_mer_stakod5',
        'f_mpa_stakod',
        'f_mpa_kallikratis',
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
        'f_mea_dieythinsi',
        'f_mea_kallikratis',
        'f_mea_tk',
        'f_mea_til',
        'f_mea_fax',
        'f_mea_email',
        'f_mea_epipedo_morfosis',
        'f_mea_professional_education',
        'f_mea_expertise_field',
        'f_mea_subject_area',
        'f_mea_subject_group',
        'f_mea_education_agency',
        'f_mea_education_date_from',
        'f_mea_education_date_to',
        'f_mea_duration',
        'f_mea_education_year',
        'f_mea_fl1',
        'f_mea_fl2',
        'f_mea_fl3',
        'f_mea_fl4',
        'f_mea_pc',
        'f_mea_pc_other',
        'f_date_proslipsis',
        'f_date_time_proslipsis',
        'f_orario',
        'f_week_hours',
        'f_orario_dialeima',
        'f_wres_external',
        'f_eidikothta_programma',
        'f_eidikothta',
        'f_proipiresia',
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_proti_ergasia',
        'f_sxesh_apasxolhshs',
        'f_orismenou_apo',
        'f_orismenou_ews',
        'f_kathestos_apasxolhshs',
        'f_foreas_topothetisis',
        'f_topothetisi_epistoli',
        'f_replace_programa',
        'f_replace_programa_afm',
        'f_replace_programa_amka',
        'f_epidoma_anergeias',
        'f_ypiresia_oaed_anergeias',
        'f_comments',
        'f_mek_afm',
        'f_eponymo',
        'f_onoma',
        'f_idiotita',
        'f_dieythinsi',
        'f_afm_bebaiwn',
        'f_file',
        'f_foreign_file',
    ];

    // ==================== Program ====================

    public function getProgramCode(): ?string
    {
        return $this->get('f_programma_benefit');
    }

    /**
     * Program code (e.g. 363201-24).
     */
    public function setProgramCode(string $value): static
    {
        return $this->set('f_programma_benefit', $value);
    }

    // ==================== Vocational Training ====================

    public function getProfessionalTraining(): ?string
    {
        return $this->get('f_mea_professional_education');
    }

    /**
     * Whether the beneficiary has vocational training.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setProfessionalTraining(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_mea_professional_education', $value);
    }

    public function getTrainingSubject(): ?string
    {
        return $this->get('f_mea_expertise_field');
    }

    public function setTrainingSubject(string $value): static
    {
        return $this->set('f_mea_expertise_field', $value);
    }

    public function getSubjectArea(): ?string
    {
        return $this->get('f_mea_subject_area');
    }

    /**
     * Subject area code (see ParameterLookup::SUBJECT_AREA).
     */
    public function setSubjectArea(string $value): static
    {
        return $this->set('f_mea_subject_area', $value);
    }

    public function getSubjectGroup(): ?string
    {
        return $this->get('f_mea_subject_group');
    }

    /**
     * Subject group code (see ParameterLookup::SUBJECT_GROUP).
     */
    public function setSubjectGroup(string $value): static
    {
        return $this->set('f_mea_subject_group', $value);
    }

    public function getEducationAgency(): ?string
    {
        return $this->get('f_mea_education_agency');
    }

    /**
     * Training agency type code (see ParameterLookup::EDUCATION_AGENCY).
     */
    public function setEducationAgency(string $value): static
    {
        return $this->set('f_mea_education_agency', $value);
    }

    public function getTrainingDateFrom(): ?string
    {
        return $this->get('f_mea_education_date_from');
    }

    public function setTrainingDateFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_education_date_from', $date);
    }

    public function getTrainingDateTo(): ?string
    {
        return $this->get('f_mea_education_date_to');
    }

    public function setTrainingDateTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_mea_education_date_to', $date);
    }

    public function getTrainingDuration(): ?string
    {
        return $this->get('f_mea_duration');
    }

    /**
     * Training duration in hours.
     */
    public function setTrainingDuration(int|string $value): static
    {
        return $this->set('f_mea_duration', (string) $value);
    }

    public function getTrainingYear(): ?string
    {
        return $this->get('f_mea_education_year');
    }

    /**
     * Year the training was completed.
     */
    public function setTrainingYear(int|string $value): static
    {
        return $this->set('f_mea_education_year', (string) $value);
    }

    public function getForeignLanguage1(): ?string
    {
        return $this->get('f_mea_fl1');
    }

    public function setForeignLanguage1(string $value): static
    {
        return $this->set('f_mea_fl1', $value);
    }

    public function getForeignLanguage2(): ?string
    {
        return $this->get('f_mea_fl2');
    }

    public function setForeignLanguage2(string $value): static
    {
        return $this->set('f_mea_fl2', $value);
    }

    public function getForeignLanguage3(): ?string
    {
        return $this->get('f_mea_fl3');
    }

    public function setForeignLanguage3(string $value): static
    {
        return $this->set('f_mea_fl3', $value);
    }

    public function getForeignLanguage4(): ?string
    {
        return $this->get('f_mea_fl4');
    }

    public function setForeignLanguage4(string $value): static
    {
        return $this->set('f_mea_fl4', $value);
    }

    public function getComputerSkills(): ?string
    {
        return $this->get('f_mea_pc');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setComputerSkills(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_mea_pc', $value);
    }

    public function getComputerSkillsOther(): ?string
    {
        return $this->get('f_mea_pc_other');
    }

    public function setComputerSkillsOther(string $value): static
    {
        return $this->set('f_mea_pc_other', $value);
    }

    // ==================== Placement ====================

    public function getPlacementDate(): ?string
    {
        return $this->get('f_date_proslipsis');
    }

    public function setPlacementDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_proslipsis', $date);
    }

    public function getPlacementTime(): ?string
    {
        return $this->get('f_date_time_proslipsis');
    }

    /**
     * Work start time on the first day (HH:MM).
     */
    public function setPlacementTime(string $value): static
    {
        return $this->set('f_date_time_proslipsis', $value);
    }

    public function getWeeklyHours(): ?string
    {
        return $this->get('f_week_hours');
    }

    /**
     * Weekly working hours as free text (e.g. "40").
     */
    public function setWeeklyHours(int|string $value): static
    {
        return $this->set('f_week_hours', (string) $value);
    }

    public function getScheduleAttached(): ?string
    {
        return $this->get('f_wres_external');
    }

    /**
     * Whether the working days and hours are given in an attached file.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setScheduleAttached(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_wres_external', $value);
    }

    public function getProgramSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta_programma');
    }

    /**
     * Specialty code of the public call (e.g. 101ΠΕ01).
     */
    public function setProgramSpecialtyCode(string $value): static
    {
        return $this->set('f_eidikothta_programma', $value);
    }

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta');
    }

    public function setSpecialtyCode(string $value): static
    {
        return $this->set('f_eidikothta', $value);
    }

    public function getExperienceYears(): ?int
    {
        return $this->int('f_proipiresia');
    }

    public function setExperienceYears(int $value): static
    {
        return $this->set('f_proipiresia', (string) $value);
    }

    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    public function getHourlyWage(): ?float
    {
        return $this->greekFloat('f_hour_apodoxes');
    }

    public function setHourlyWage(float $amount): static
    {
        return $this->set('f_hour_apodoxes', $amount);
    }

    public function getFirstEmployment(): ?string
    {
        return $this->get('f_proti_ergasia');
    }

    /**
     * Whether this is the first employment as a salaried worker.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setFirstEmployment(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_proti_ergasia', $value);
    }

    public function getEmploymentType(): ?string
    {
        return $this->get('f_sxesh_apasxolhshs');
    }

    /**
     * @param EmploymentType|string|int $value 0=Indefinite, 1=Fixed-term, 2=Project (or use EmploymentType enum). Only 0-2 are accepted by ERGANI.
     */
    public function setEmploymentType(EmploymentType|string|int $value): static
    {
        if ($value instanceof EmploymentType) {
            $value = $value->value;
        }

        return $this->set('f_sxesh_apasxolhshs', (string) $value);
    }

    public function getStartDate(): ?string
    {
        return $this->get('f_orismenou_apo');
    }

    /**
     * Fixed-term contract start date.
     */
    public function setStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_apo', $date);
    }

    public function getEndDate(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    /**
     * Fixed-term contract end date.
     */
    public function setEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_ews', $date);
    }

    public function getEmploymentStatus(): ?string
    {
        return $this->get('f_kathestos_apasxolhshs');
    }

    /**
     * @param EmploymentStatus|string|int $value 0=Full-time, 1=Part-time, 2=Rotational (or use EmploymentStatus enum). Only 0-2 are accepted by ERGANI.
     */
    public function setEmploymentStatus(EmploymentStatus|string|int $value): static
    {
        if ($value instanceof EmploymentStatus) {
            $value = $value->value;
        }

        return $this->set('f_kathestos_apasxolhshs', (string) $value);
    }

    public function getPlacementAgency(): ?string
    {
        return $this->get('f_foreas_topothetisis');
    }

    /**
     * Agency where the beneficiary is placed.
     */
    public function setPlacementAgency(string $value): static
    {
        return $this->set('f_foreas_topothetisis', $value);
    }

    public function getDypaReferral(): ?string
    {
        return $this->get('f_topothetisi_epistoli');
    }

    /**
     * Whether the placement is made with a DYPA referral letter.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setDypaReferral(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_topothetisi_epistoli', $value);
    }

    public function getReplacement(): ?string
    {
        return $this->get('f_replace_programa');
    }

    /**
     * Whether the placement replaces a beneficiary of the same program.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setReplacement(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_replace_programa', $value);
    }

    public function getReplacedAfm(): ?string
    {
        return $this->get('f_replace_programa_afm');
    }

    public function setReplacedAfm(string $value): static
    {
        return $this->set('f_replace_programa_afm', $value);
    }

    public function getReplacedAmka(): ?string
    {
        return $this->get('f_replace_programa_amka');
    }

    public function setReplacedAmka(string $value): static
    {
        return $this->set('f_replace_programa_amka', $value);
    }

    public function getReceivesUnemploymentBenefit(): ?string
    {
        return $this->get('f_epidoma_anergeias');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setReceivesUnemploymentBenefit(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_epidoma_anergeias', $value);
    }

    public function getUnemploymentBenefitServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed_anergeias');
    }

    /**
     * DYPA service paying the unemployment benefit.
     */
    public function setUnemploymentBenefitServiceCode(string $value): static
    {
        return $this->set('f_ypiresia_oaed_anergeias', $value);
    }

    // ==================== Certifier ====================

    public function getCertifierLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setCertifierLastName(string $value): static
    {
        return $this->set('f_eponymo', $value);
    }

    public function getCertifierFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setCertifierFirstName(string $value): static
    {
        return $this->set('f_onoma', $value);
    }

    public function getCertifierCapacity(): ?string
    {
        return $this->get('f_idiotita');
    }

    public function setCertifierCapacity(string $value): static
    {
        return $this->set('f_idiotita', $value);
    }

    public function getCertifierAddress(): ?string
    {
        return $this->get('f_dieythinsi');
    }

    public function setCertifierAddress(string $value): static
    {
        return $this->set('f_dieythinsi', $value);
    }

    public function getCertifierAfm(): ?string
    {
        return $this->get('f_afm_bebaiwn');
    }

    public function setCertifierAfm(string $value): static
    {
        return $this->set('f_afm_bebaiwn', $value);
    }

    /**
     * Set the foreign language codes (up to four, see ParameterLookup::LANGUAGE).
     *
     * @param array<int, string> $codes
     */
    public function setForeignLanguages(array $codes): static
    {
        foreach (array_pad(array_slice($codes, 0, 4), 4, '') as $i => $code) {
            $this->set('f_mea_fl' . ($i + 1), $code);
        }

        return $this;
    }

    /**
     * @return array<int, string> Non-empty foreign language codes
     */
    public function getForeignLanguages(): array
    {
        $codes = [];
        for ($i = 1; $i <= 4; $i++) {
            $code = $this->get('f_mea_fl' . $i);
            if (is_string($code) && $code !== '') {
                $codes[] = $code;
            }
        }

        return $codes;
    }

    /**
     * Set the vocational training period in one call.
     */
    public function setTrainingPeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setTrainingDateFrom($from)->setTrainingDateTo($to);
    }

    /**
     * Set the fixed-term period in one call.
     */
    public function setFixedTermPeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setStartDate($from)->setEndDate($to);
    }

    /**
     * Mark the placement as a replacement of another beneficiary of the same program.
     */
    public function replacesBeneficiary(string $afm, string $amka): static
    {
        return $this->setReplacement(true)->setReplacedAfm($afm)->setReplacedAmka($amka);
    }

    /**
     * Mark the beneficiary as receiving unemployment benefit from the given DYPA service.
     */
    public function receivesUnemploymentBenefitFrom(string $dypaServiceCode): static
    {
        return $this->setReceivesUnemploymentBenefit(true)->setUnemploymentBenefitServiceCode($dypaServiceCode);
    }
}
