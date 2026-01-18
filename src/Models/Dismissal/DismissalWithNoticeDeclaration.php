<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\DismissalWithNoticeDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Dismissal\Concerns\HasCollectiveDismissal;
use OxygenSuite\OxygenErgani\Models\Dismissal\Concerns\HasEmploymentClassification;
use OxygenSuite\OxygenErgani\Models\Dismissal\Concerns\HasNoticePeriod;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasCompensation;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasFormFile;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasSalary;

/**
 * Dismissal with notice declaration model for E6NMP_v1 schema.
 *
 * Used for employer-initiated terminations with advance notice period.
 * The employee continues working during the notice period.
 *
 * @see xsd/E6NMP_v1.xsd
 *
 * @method static DismissalWithNoticeDeclarationFactory factory(int $count = 1)
 */
class DismissalWithNoticeDeclaration extends Declaration
{
    use HasFactory;
    use HasEmploymentClassification;
    use HasNoticePeriod;
    use HasCollectiveDismissal;
    use HasSalary;
    use HasCompensation;
    use HasFormFile;

    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
        'f_posoapozimiosis' => 'greek_float',
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

        // Employment Classification (HasEmploymentClassification)
        'f_kathestosapasxolisis',
        'f_xaraktirismos',
        'f_eidikothta',

        // Notice Period (HasNoticePeriod)
        'f_proidopoihshdate',
        'f_minesproidopoihsh',

        // Collective Dismissal (HasCollectiveDismissal)
        'f_omadiki',
        'f_omadikiarithmos',
        'f_omadikidate',

        // Employment Dates
        'f_proslipsidate',
        'f_apolysisdate',

        // Salary (HasSalary)
        'f_apodoxes',

        // Compensation (HasCompensation)
        'f_posoapozimiosis',

        // Comments and Files
        'f_comments',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    /**
     * Get the employee's hiring date.
     */
    public function getHiringDate(): ?string
    {
        return $this->get('f_proslipsidate');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setHiringDate(string $date): static
    {
        return $this->set('f_proslipsidate', $date);
    }

    /**
     * Get the end date of the notice period (dismissal effective date).
     */
    public function getDismissalDate(): ?string
    {
        return $this->get('f_apolysisdate');
    }

    /**
     * Set the end date of the notice period (dismissal effective date).
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setDismissalDate(string $date): static
    {
        return $this->set('f_apolysisdate', $date);
    }
}
