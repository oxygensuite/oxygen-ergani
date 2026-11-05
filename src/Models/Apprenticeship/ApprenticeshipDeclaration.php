<?php

namespace OxygenSuite\OxygenErgani\Models\Apprenticeship;

use DateTimeInterface;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiary;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorBookNumber;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorFile;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasPlacement;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramCertifier;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramProvider;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramSignOff;

/**
 * Apprenticeship start declaration model for E34_v1 schema.
 *
 * Announces the start of an apprenticeship (μαθητεία) for a student of a vocational school (E3.4).
 *
 * Note: for E3.4 the education level (`setEducationLevel()`) only accepts 0-4 and the employer
 * organization (`setEmployerOrganization()`) is limited to 20 characters.
 *
 * @see xsd/E34_v1.xsd
 */
class ApprenticeshipDeclaration extends Model
{
    use HasFactory;
    use HasBeneficiary;
    use HasMinorBookNumber;
    use HasMinorFile;
    use HasProgramCertifier;
    use HasProgramProvider;
    use HasProgramSignOff;
    use HasPlacement;

    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_ypiresia_oaed',
        'f_ypiresia_sepe',
        'f_programma_novitiate',
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
        'f_mea_ar_vivliou_anilikou',
        'f_mea_dieythinsi',
        'f_mea_kallikratis',
        'f_mea_tk',
        'f_mea_til',
        'f_mea_fax',
        'f_mea_email',
        'f_mea_epipedo_morfosis',
        'f_mea_noviciate_sxolh',
        'f_mea_noviciate_sxolh_other',
        'f_mea_noviciate_omada',
        'f_mea_noviciate_omada_other',
        'f_date_proslipsis',
        'f_date_time_proslipsis',
        'f_orario',
        'f_week_hours',
        'f_orario_dialeima',
        'f_wres_external',
        'f_novitiate_education',
        'f_apodoxes',
        'f_orismenou_ews',
        'f_contract',
        'f_comments',
        'f_mek_afm',
        'f_eponymo',
        'f_onoma',
        'f_idiotita',
        'f_dieythinsi',
        'f_afm_bebaiwn',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    // ==================== Program ====================

    public function getProgramCode(): ?string
    {
        return $this->get('f_programma_novitiate');
    }

    /**
     * Apprenticeship program code (3 digits).
     */
    public function setProgramCode(string $value): static
    {
        return $this->set('f_programma_novitiate', $value);
    }

    // ==================== Apprentice ====================

    public function getSchoolCode(): ?string
    {
        return $this->get('f_mea_noviciate_sxolh');
    }

    /**
     * Vocational school code (4 digits); leave empty and use setSchoolOther() if not listed.
     */
    public function setSchoolCode(string $value): static
    {
        return $this->set('f_mea_noviciate_sxolh', $value);
    }

    public function getSchoolOther(): ?string
    {
        return $this->get('f_mea_noviciate_sxolh_other');
    }

    /**
     * School name as free text when the school code is not listed (max 50 characters).
     */
    public function setSchoolOther(string $value): static
    {
        return $this->set('f_mea_noviciate_sxolh_other', $value);
    }

    public function getSpecialtyGroupCode(): ?string
    {
        return $this->get('f_mea_noviciate_omada');
    }

    /**
     * Specialty group / sector code (4 digits); leave empty and use setSpecialtyGroupOther() if not listed.
     */
    public function setSpecialtyGroupCode(string $value): static
    {
        return $this->set('f_mea_noviciate_omada', $value);
    }

    public function getSpecialtyGroupOther(): ?string
    {
        return $this->get('f_mea_noviciate_omada_other');
    }

    /**
     * Specialty group as free text when the group code is not listed (max 50 characters).
     */
    public function setSpecialtyGroupOther(string $value): static
    {
        return $this->set('f_mea_noviciate_omada_other', $value);
    }

    // ==================== Apprenticeship ====================

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_novitiate_education');
    }

    /**
     * Apprenticeship specialty code (4 digits).
     */
    public function setSpecialtyCode(string $value): static
    {
        return $this->set('f_novitiate_education', $value);
    }

    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    /**
     * Gross monthly apprentice wage.
     */
    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    public function getEndDate(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    /**
     * Apprenticeship end date.
     */
    public function setEndDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_orismenou_ews', $date);
    }

    public function getContractNumber(): ?string
    {
        return $this->get('f_contract');
    }

    /**
     * Apprenticeship contract number / reference (required, max 50 characters).
     */
    public function setContractNumber(string $value): static
    {
        return $this->set('f_contract', $value);
    }

    /**
     * Set the apprenticeship period in one call.
     */
    public function setApprenticeshipPeriod(DateTimeInterface|string $from, DateTimeInterface|string $to): static
    {
        return $this->setPlacementDate($from)->setEndDate($to);
    }
}
