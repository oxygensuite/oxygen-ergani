<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\EndOfLoanDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Dismissal\Concerns\HasLoanDetails;

/**
 * End of employee loan declaration model for E6LD_v1 schema.
 *
 * Used when a loaned employee returns to their original employer.
 * Does NOT include salary, severance, or form file - loan termination
 * simply returns the employee to the original employer.
 *
 * @see xsd/E6LD_v1.xsd
 *
 * @method static EndOfLoanDeclarationFactory factory(int $count = 1)
 */
class EndOfLoanDeclaration extends Declaration
{
    use HasFactory;
    use HasLoanDetails;

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

        // Loan Details (HasLoanDetails)
        'f_borrow_type',
        'f_borrow_date_from',
        'f_borrow_date_to',
        'f_borrow_company_afm',
        'f_borrow_company_eponimia',

        // Comments and Files
        'f_comments',
        'f_foreign_file',
        'f_young_file',
    ];
}
