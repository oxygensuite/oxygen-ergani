<?php

namespace OxygenSuite\OxygenErgani\Models\Training;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiary;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasFixedTermPeriod;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorFile;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasPlacement;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramProvider;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramSignOff;
use OxygenSuite\OxygenErgani\Models\Training\Concerns\HasTrainingBeneficiaryDetails;

/**
 * Practical training start declaration model for E33_v1 schema.
 *
 * Announces the start of the practical training (at a business) that follows the theoretical
 * training of a program beneficiary (E3.3). The submitting business is the host; the
 * theoretical training provider is identified through the `setTheory*()` fields.
 *
 * @see xsd/E33_v1.xsd
 */
class PracticalTrainingDeclaration extends Model
{
    use HasFactory;
    use HasBeneficiary;
    use HasMinorFile;
    use HasProgramProvider;
    use HasProgramSignOff;
    use HasTrainingBeneficiaryDetails;
    use HasFixedTermPeriod;
    use HasPlacement;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_ypiresia_oaed',
        'f_ypiresia_sepe',
        'f_programma_voucher',
        'f_mer_organization',
        'f_mer_stakod1',
        'f_mer_stakod2',
        'f_mer_stakod3',
        'f_mer_stakod4',
        'f_mer_stakod5',
        'f_mpa_stakod',
        'f_mpa_kallikratis',
        'f_mpa_men',
        'f_mpa_women',
        'f_mpa_minors',
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
        'f_date_time_proslipsis',
        'f_orario',
        'f_week_hours',
        'f_orario_dialeima',
        'f_wres_external',
        'f_replace_programa',
        'f_eidikothta',
        'f_orismenou_apo',
        'f_orismenou_ews',
        'f_comments',
        'f_theory_eopep_code',
        'f_theory_edu_agency_category',
        'f_theory_mer_afm',
        'f_theory_mpa_aa',
        'f_theory_mpa_kallikratis',
        'f_mek_afm',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    // ==================== Branch Workforce ====================

    public function getBranchMenCount(): ?string
    {
        return $this->get('f_mpa_men');
    }

    /**
     * Number of men employed at the branch.
     */
    public function setBranchMenCount(int|string $value): static
    {
        return $this->set('f_mpa_men', (string) $value);
    }

    public function getBranchWomenCount(): ?string
    {
        return $this->get('f_mpa_women');
    }

    /**
     * Number of women employed at the branch.
     */
    public function setBranchWomenCount(int|string $value): static
    {
        return $this->set('f_mpa_women', (string) $value);
    }

    public function getBranchMinorsCount(): ?string
    {
        return $this->get('f_mpa_minors');
    }

    /**
     * Number of minors employed at the branch.
     */
    public function setBranchMinorsCount(int|string $value): static
    {
        return $this->set('f_mpa_minors', (string) $value);
    }

    /**
     * Set the branch workforce counts in one call.
     */
    public function setBranchWorkforce(int $men, int $women, int $minors = 0): static
    {
        return $this->setBranchMenCount($men)
            ->setBranchWomenCount($women)
            ->setBranchMinorsCount($minors);
    }

    // ==================== Practical Training ====================

    public function getReplacement(): ?string
    {
        return $this->get('f_replace_programa');
    }

    /**
     * Whether the beneficiary replaces another program beneficiary.
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

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta');
    }

    /**
     * Specialty code (up to 6 digits, see ParameterLookup::SPECIALTY).
     */
    public function setSpecialtyCode(string $value): static
    {
        return $this->set('f_eidikothta', $value);
    }

    // ==================== Theoretical Training Provider ====================

    public function getTheoryEopepCode(): ?string
    {
        return $this->get('f_theory_eopep_code');
    }

    /**
     * EOPPEP code of the theoretical training provider.
     */
    public function setTheoryEopepCode(string $value): static
    {
        return $this->set('f_theory_eopep_code', $value);
    }

    public function getTheoryProviderCategory(): ?string
    {
        return $this->get('f_theory_edu_agency_category');
    }

    /**
     * Category code of the theoretical training provider.
     */
    public function setTheoryProviderCategory(string $value): static
    {
        return $this->set('f_theory_edu_agency_category', $value);
    }

    public function getTheoryProviderAfm(): ?string
    {
        return $this->get('f_theory_mer_afm');
    }

    /**
     * AFM of the theoretical training provider.
     */
    public function setTheoryProviderAfm(string $value): static
    {
        return $this->set('f_theory_mer_afm', $value);
    }

    public function getTheoryProviderBranch(): ?string
    {
        return $this->get('f_theory_mpa_aa');
    }

    /**
     * Branch number (Α/Α παραρτήματος) of the theoretical training provider.
     */
    public function setTheoryProviderBranch(int|string $value): static
    {
        return $this->set('f_theory_mpa_aa', (string) $value);
    }

    public function getTheoryProviderMunicipalityCode(): ?string
    {
        return $this->get('f_theory_mpa_kallikratis');
    }

    /**
     * Kallikratis code of the theoretical training provider's branch (8 digits).
     */
    public function setTheoryProviderMunicipalityCode(string $value): static
    {
        return $this->set('f_theory_mpa_kallikratis', $value);
    }
}
