<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal;

use DateTime;
use OxygenSuite\OxygenErgani\Factories\Dismissal\TrialPeriodTerminationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasFormFile;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasSalary;

/**
 * Trial period termination declaration model for E6LT_v1 schema.
 *
 * Used when employment automatically terminates at the end of trial period.
 * Does NOT include severance pay - trial period termination requires no compensation.
 *
 * @see xsd/E6LT_v1.xsd
 *
 * @method static TrialPeriodTerminationDeclarationFactory factory(int $count = 1)
 */
class TrialPeriodTerminationDeclaration extends Declaration
{
    use HasFactory;
    use HasSalary;
    use HasFormFile;

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

        // Employment Dates
        'f_proslipsidate',
        'f_apolysisdate',

        // Salary (HasSalary)
        'f_apodoxes',

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
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setHiringDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_proslipsidate', $date);
    }

    /**
     * Get the termination date (end of trial period).
     */
    public function getTerminationDate(): ?string
    {
        return $this->get('f_apolysisdate');
    }

    /**
     * Set the termination date (end of trial period).
     *
     * @param DateTime|string $date Date in DD/MM/YYYY format
     */
    public function setTerminationDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_apolysisdate', $date);
    }
}
