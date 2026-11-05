<?php

namespace OxygenSuite\OxygenErgani\Models\Training;

use DateTimeInterface;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasBeneficiaryAfm;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorFile;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramAttachments;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramReference;
use OxygenSuite\OxygenErgani\Models\Training\Concerns\HasPracticeBusiness;

/**
 * Theoretical training employer change declaration model for E32T_v1 schema.
 *
 * Changes the practical training business of a beneficiary declared with a previous E3.2,
 * referenced through `setRelatedProtocol()` / `setRelatedDate()`. The new business details
 * use the same setters as E3.2 (`setPracticeCompanyName()`, `setPracticePeriod()`, ...).
 *
 * @see xsd/E32T_v1.xsd
 */
class TheoreticalTrainingEmployerChangeDeclaration extends Model
{
    use HasBeneficiaryAfm;
    use HasFactory;
    use HasMinorFile;
    use HasPracticeBusiness;
    use HasProgramAttachments;
    use HasProgramReference;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_protocol_sx',
        'f_date_ypovolis_sx',
        'f_mea_afm',
        'f_cpractice_date',
        'f_cpractice_mer_eponimia',
        'f_cpractice_mer_afm',
        'f_cpractice_mer_doy',
        'f_cpractice_mer_ame',
        'f_cpractice_mer_no_ame',
        'f_cpractice_mpa_address',
        'f_cpractice_mpa_kallikratis',
        'f_cpractice_mpa_tk',
        'f_cpractice_mpa_til',
        'f_cpractice_mpa_fax',
        'f_cpractice_mpa_email',
        'f_cpractice_date_from',
        'f_cpractice_date_to',
        'f_mek_afm',
        'f_file',
        'f_foreign_file',
        'f_young_file',
    ];

    public function getChangeDate(): ?string
    {
        return $this->get('f_cpractice_date');
    }

    /**
     * Date of the employer change.
     */
    public function setChangeDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_cpractice_date', $date);
    }

    protected function practiceField(string $suffix): string
    {
        return 'f_cpractice_' . $suffix;
    }
}
