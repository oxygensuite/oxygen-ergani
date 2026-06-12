<?php

namespace OxygenSuite\OxygenErgani\Models\Termination;

use OxygenSuite\OxygenErgani\Factories\Termination\MandatoryRetirementDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasCompensation;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasFormFile;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasSalary;

/**
 * Mandatory retirement declaration model for E5DS_v1 schema.
 *
 * Used for reporting employee departure due to mandatory retirement
 * (after 15 years of service or reaching the age limit) with compensation.
 *
 * @see xsd/E5DS_v1.xsd
 *
 * @method static MandatoryRetirementDeclarationFactory factory(int $count = 1)
 */
class MandatoryRetirementDeclaration extends Declaration
{
    use HasFactory;
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

        // Employment Details
        'f_xaraktirismos',
        'f_sxeshapasxolisis',
        'f_orismenou_apo',
        'f_orismenou_ews',
        'f_kathestosapasxolisis',
        'f_eidikothta',
        'f_proslipsidate',
        'f_apoxwrisidate',
        'f_apodoxes',
        'f_posoapozimiosis',

        // Comments and Files
        'f_comments',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];
}
