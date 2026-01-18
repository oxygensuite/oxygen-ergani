<?php

namespace OxygenSuite\OxygenErgani\Models\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\FixedTermTerminationReason;
use OxygenSuite\OxygenErgani\Factories\Termination\FixedTermTerminationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Dismissal\Concerns\HasEmploymentClassification;
use OxygenSuite\OxygenErgani\Models\Dismissal\Declaration as DismissalDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\Concerns\HasSalary;

/**
 * Fixed-term contract termination declaration model for E7N_v1 schema.
 *
 * Used for reporting fixed-term employment contract terminations to ERGANI.
 * This is distinct from E5 (employee-initiated) and E6 (employer-initiated dismissals).
 * E7N handles cases where a fixed-term contract naturally expires or is terminated early.
 *
 * Note: f_sxeshapasxolisis only accepts values 1 (fixed-term) or 2 (project) - NOT 0 (indefinite).
 * Note: E7N uses f_apolysisdate (like E6) not f_apoxwrisidate (like E5).
 * Note: E7N does NOT have f_file (signed form file) - only f_foreign_file and f_young_file.
 *
 * @see xsd/E7N_v1.xsd
 *
 * @method static FixedTermTerminationDeclarationFactory factory(int $count = 1)
 */
class FixedTermTerminationDeclaration extends DismissalDeclaration
{
    use HasFactory;
    use HasEmploymentClassification;
    use HasSalary;

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

        // E7 Specific - Employment Classification
        'f_xaraktirismos',
        'f_sxeshapasxolisis',
        'f_kathestosapasxolisis',
        'f_oros',
        'f_eidikothta',

        // E7 Specific - Salary and Dates
        'f_apodoxes',
        'f_proslipsidate',
        'f_lixisymbashdate',
        'f_apolysisdate',

        // Comments and Termination Reason
        'f_comments',
        'f_logosperatosis',
        'f_logosperatosiscomments',

        // Files
        'f_foreign_file',
        'f_young_file',
    ];

    // ==================== Employment Relationship ====================

    /**
     * Get the employment relationship type.
     *
     * @return string|null 1=Fixed-term, 2=Project
     */
    public function getEmploymentRelationship(): ?string
    {
        return $this->get('f_sxeshapasxolisis');
    }

    /**
     * Set the employment relationship type.
     *
     * Note: E7N only accepts FIXED_TERM (1) or PROJECT (2) - NOT INDEFINITE (0).
     *
     * @param EmploymentType|int|string $type 1=Fixed-term, 2=Project
     */
    public function setEmploymentRelationship(EmploymentType|int|string $type): static
    {
        if ($type instanceof EmploymentType) {
            $type = $type->value;
        }

        return $this->set('f_sxeshapasxolisis', (string) $type);
    }

    // ==================== Compensation Clause ====================

    /**
     * Whether the contract includes a compensation clause for early termination.
     *
     * Per Article 40 of Law 3986/2011, a fixed-term contract may include a clause
     * that applies indefinite contract severance rules in case of early termination.
     */
    public function hasCompensationClause(): bool
    {
        return $this->get('f_oros') === '1';
    }

    /**
     * Set whether the contract includes a compensation clause.
     *
     * @param bool $hasClause true if contract includes compensation clause
     */
    public function setCompensationClause(bool $hasClause): static
    {
        return $this->set('f_oros', $hasClause ? '1' : '0');
    }

    // ==================== Contract Dates ====================

    /**
     * Get the contractual end date of the fixed-term contract.
     */
    public function getContractEndDate(): ?string
    {
        return $this->get('f_lixisymbashdate');
    }

    /**
     * Set the contractual end date of the fixed-term contract.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setContractEndDate(string $date): static
    {
        return $this->set('f_lixisymbashdate', $date);
    }

    /**
     * Get the employee's hiring date.
     */
    public function getHiringDate(): ?string
    {
        return $this->get('f_proslipsidate');
    }

    /**
     * Set the employee's hiring date.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setHiringDate(string $date): static
    {
        return $this->set('f_proslipsidate', $date);
    }

    /**
     * Get the actual termination date.
     */
    public function getTerminationDate(): ?string
    {
        return $this->get('f_apolysisdate');
    }

    /**
     * Set the actual termination date.
     *
     * @param string $date Date in DD/MM/YYYY format
     */
    public function setTerminationDate(string $date): static
    {
        return $this->set('f_apolysisdate', $date);
    }

    // ==================== Termination Reason ====================

    /**
     * Get the termination reason code.
     *
     * @return int|null 0=Contract expiration, 3=Work completion, 4=Early by employer, 5=Early by employee, 6=Mutual agreement
     */
    public function getTerminationReason(): ?int
    {
        $value = $this->get('f_logosperatosis');

        return $value !== null ? (int) $value : null;
    }

    /**
     * Set the termination reason.
     */
    public function setTerminationReason(FixedTermTerminationReason|int $reason): static
    {
        if ($reason instanceof FixedTermTerminationReason) {
            $reason = $reason->value;
        }

        return $this->set('f_logosperatosis', (string) $reason);
    }

    /**
     * Get the termination reason comments.
     */
    public function getTerminationReasonComments(): ?string
    {
        return $this->get('f_logosperatosiscomments');
    }

    /**
     * Set the termination reason comments.
     *
     * @param string $comments Comments text (max 100 chars)
     */
    public function setTerminationReasonComments(string $comments): static
    {
        return $this->set('f_logosperatosiscomments', $comments);
    }
}
