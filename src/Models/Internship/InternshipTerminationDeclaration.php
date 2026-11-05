<?php

namespace OxygenSuite\OxygenErgani\Models\Internship;

use DateTimeInterface;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasRelatedSubmission;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasInternshipFiles;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasLegalRepresentative;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramCertifier;

/**
 * Internship termination (interruption) declaration model for E35D_v1 schema.
 *
 * Reports the early termination of an internship declared with a previous E3.5,
 * referenced through `setRelatedProtocol()` and `setRelatedDate()`.
 *
 * @see xsd/E35D_v1.xsd
 */
class InternshipTerminationDeclaration extends Model
{
    use HasFactory;
    use HasRelatedSubmission;
    use HasInternshipFiles;
    use HasLegalRepresentative;
    use HasProgramCertifier;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_afm',
        'f_diakoph_date',
        'f_diakoph_aitiologia',
        'f_eponymo',
        'f_onoma',
        'f_idiotita',
        'f_dieythinsi',
        'f_afm_bebaiwn',
        'f_afm_proswpoy',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    /**
     * @param string $afm Intern's tax identification number (AFM)
     */
    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    public function getTerminationDate(): ?string
    {
        return $this->get('f_diakoph_date');
    }

    public function setTerminationDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_diakoph_date', $date);
    }

    public function getTerminationReason(): ?string
    {
        return $this->get('f_diakoph_aitiologia');
    }

    /**
     * @param string $code Termination reason code (3 digits, e.g. '001')
     */
    public function setTerminationReason(string $code): static
    {
        return $this->set('f_diakoph_aitiologia', $code);
    }
}
