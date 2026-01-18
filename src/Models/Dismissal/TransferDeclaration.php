<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\TransferDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;

/**
 * Transfer to another company declaration model for E6M_v1 schema.
 *
 * Used when an employee is transferred to another company.
 * This is the simplest E6 form - only requires basic employee info
 * and transfer details (date and receiving company).
 *
 * Does NOT include salary, severance, or form file.
 *
 * @see xsd/E6M_v1.xsd
 *
 * @method static TransferDeclarationFactory factory(int $count = 1)
 */
class TransferDeclaration extends Declaration
{
    use HasFactory;

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

        // Transfer Details
        'f_date_metabibashs',
        'f_transfer_company_afm',
        'f_transfer_company_eponimia',

        // Comments and Files
        'f_comments',
        'f_foreign_file',
        'f_young_file',
    ];

    // ==================== Transfer Details ====================

    /**
     * Get the transfer date.
     */
    public function getTransferDate(): ?string
    {
        return $this->get('f_date_metabibashs');
    }

    /**
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setTransferDate(string $date): static
    {
        return $this->set('f_date_metabibashs', $date);
    }

    /**
     * Get the receiving company's AFM (tax identification number).
     */
    public function getTransferCompanyAfm(): ?string
    {
        return $this->get('f_transfer_company_afm');
    }

    /**
     * @param string $afm 9-digit AFM
     */
    public function setTransferCompanyAfm(string $afm): static
    {
        return $this->set('f_transfer_company_afm', $afm);
    }

    /**
     * Get the receiving company's name.
     */
    public function getTransferCompanyName(): ?string
    {
        return $this->get('f_transfer_company_eponimia');
    }

    /**
     * @param string $name Company name (max 150 chars)
     */
    public function setTransferCompanyName(string $name): static
    {
        return $this->set('f_transfer_company_eponimia', $name);
    }
}
