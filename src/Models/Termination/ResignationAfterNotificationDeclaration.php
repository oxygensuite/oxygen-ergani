<?php

namespace OxygenSuite\OxygenErgani\Models\Termination;

use OxygenSuite\OxygenErgani\Factories\Termination\ResignationAfterNotificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasNotificationReference;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasSalary;

/**
 * Resignation after notification declaration model for E5AO_v1 schema.
 *
 * Used for reporting employee voluntary resignations that follow a previous E5O notification.
 * This form links to the E5O submission and confirms the departure.
 *
 * Note: Employment type (f_sxeshapasxolisis) only allows 0=Indefinite or 1=Fixed-term (no Project).
 *
 * @see xsd/E5AO_v1.xsd
 *
 * @method static ResignationAfterNotificationDeclarationFactory factory(int $count = 1)
 */
class ResignationAfterNotificationDeclaration extends Declaration
{
    use HasFactory;
    use HasSalary;
    use HasNotificationReference;

    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
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

        // Notification Reference
        'f_oxlhsh_protocol',
        'f_oxlhsh_date_ypovolis',

        // Comments and Files
        'f_comments',
        'f_foreign_file',
        'f_young_file',
    ];
}
