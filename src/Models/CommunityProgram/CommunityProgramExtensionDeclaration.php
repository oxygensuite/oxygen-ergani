<?php

namespace OxygenSuite\OxygenErgani\Models\CommunityProgram;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiaryAfm;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramAttachments;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramCertifier;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramReference;

/**
 * Community-benefit program employment extension declaration model for E31E_v1 schema.
 *
 * Extends the employment of a beneficiary declared with a previous E3.1, referenced through
 * `setRelatedProtocol()` / `setRelatedDate()`.
 *
 * @see xsd/E31E_v1.xsd
 */
class CommunityProgramExtensionDeclaration extends Model
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
        'f_enlong_date',
        'f_mek_afm',
        'f_eponymo',
        'f_onoma',
        'f_idiotita',
        'f_dieythinsi',
        'f_afm_bebaiwn',
        'f_file',
        'f_foreign_file',
    ];

    public function getExtensionDate(): ?string
    {
        return $this->get('f_enlong_date');
    }

    /**
     * Date until which the employment is extended.
     */
    public function setExtensionDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_enlong_date', $date);
    }
}
