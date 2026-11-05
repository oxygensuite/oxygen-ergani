<?php

namespace OxygenSuite\OxygenErgani\Models\Training;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiaryAfm;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorFile;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramAttachments;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramReference;

/**
 * Theoretical training interruption declaration model for E32D_v1 schema.
 *
 * Reports the interruption of a training declared with a previous E3.2, referenced through
 * `setRelatedProtocol()` / `setRelatedDate()`, with the hours and insurance days completed.
 *
 * @see xsd/E32D_v1.xsd
 */
class TheoreticalTrainingInterruptionDeclaration extends Model
{
    use HasBeneficiaryAfm;
    use HasFactory;
    use HasMinorFile;
    use HasProgramAttachments;
    use HasProgramReference;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_mea_afm',
        'f_diakoph_date',
        'f_diakoph_total_hours_theory',
        'f_diakoph_total_hours_practice',
        'f_diakoph_total_insurance_days',
        'f_diakoph_aitiologia_other',
        'f_mek_afm',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    public function getInterruptionDate(): ?string
    {
        return $this->get('f_diakoph_date');
    }

    public function setInterruptionDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_diakoph_date', $date);
    }

    public function getTheoryHours(): ?string
    {
        return $this->get('f_diakoph_total_hours_theory');
    }

    /**
     * Theoretical training hours completed until the interruption (up to 3 digits).
     */
    public function setTheoryHours(int|string $value): static
    {
        return $this->set('f_diakoph_total_hours_theory', (string) $value);
    }

    public function getPracticeHours(): ?string
    {
        return $this->get('f_diakoph_total_hours_practice');
    }

    /**
     * Practical training hours completed until the interruption (up to 3 digits, may be empty).
     */
    public function setPracticeHours(int|string $value): static
    {
        return $this->set('f_diakoph_total_hours_practice', (string) $value);
    }

    public function getInsuranceDays(): ?string
    {
        return $this->get('f_diakoph_total_insurance_days');
    }

    /**
     * Insurance days completed until the interruption (up to 3 digits, may be empty).
     */
    public function setInsuranceDays(int|string $value): static
    {
        return $this->set('f_diakoph_total_insurance_days', (string) $value);
    }

    public function getInterruptionReason(): ?string
    {
        return $this->get('f_diakoph_aitiologia_other');
    }

    /**
     * Interruption reason as free text (max 200 characters).
     */
    public function setInterruptionReason(string $value): static
    {
        return $this->set('f_diakoph_aitiologia_other', $value);
    }
}
