<?php

namespace OxygenSuite\OxygenErgani\Models\CommunityProgram;

use DateTimeInterface;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiaryAfm;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramAttachments;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramCertifier;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramReference;

/**
 * Community-benefit program employment interruption declaration model for E31D_v1 schema.
 *
 * Reports the interruption of a beneficiary's employment declared with a previous E3.1,
 * referenced through `setRelatedProtocol()` / `setRelatedDate()`.
 *
 * @see xsd/E31D_v1.xsd
 */
class CommunityProgramInterruptionDeclaration extends Model
{
    use HasBeneficiaryAfm;
    use HasFactory;
    use HasProgramAttachments;
    use HasProgramCertifier;
    use HasProgramReference;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_mea_afm',
        'f_diakoph_date',
        'f_diakoph_aitiologia',
        'f_mek_afm',
        'f_eponymo',
        'f_onoma',
        'f_idiotita',
        'f_dieythinsi',
        'f_afm_bebaiwn',
        'f_file',
        'f_foreign_file',
    ];

    public function getInterruptionDate(): ?string
    {
        return $this->get('f_diakoph_date');
    }

    public function setInterruptionDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_diakoph_date', $date);
    }

    public function getInterruptionReason(): ?string
    {
        return $this->get('f_diakoph_aitiologia');
    }

    /**
     * Interruption reason code (3 digits).
     */
    public function setInterruptionReason(string $code): static
    {
        return $this->set('f_diakoph_aitiologia', $code);
    }
}
