<?php

namespace OxygenSuite\OxygenErgani\Models\Internship;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasCertifier;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasInternshipFiles;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasLegalRepresentative;
use OxygenSuite\OxygenErgani\Models\Internship\Concerns\HasWeeklySchedule;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Internship schedule change (time organisation change) declaration model for E35T_v1 schema.
 *
 * Reports a new weekly schedule for an internship declared with a previous E3.5,
 * referenced through `setRelatedProtocol()` and `setRelatedDate()`.
 *
 * @see xsd/E35T_v1.xsd
 */
class InternshipScheduleChangeDeclaration extends Model
{
    use HasCertifier;
    use HasFactory;
    use HasInternshipFiles;
    use HasLegalRepresentative;
    use HasWeeklySchedule;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_afm',
        'f_trpopoihsh_date',
        'f_time_from_1',
        'f_time_to_1',
        'f_time_from_2',
        'f_time_to_2',
        'f_time_from_3',
        'f_time_to_3',
        'f_time_from_4',
        'f_time_to_4',
        'f_time_from_5',
        'f_time_to_5',
        'f_time_from_6',
        'f_time_to_6',
        'f_time_from_7',
        'f_time_to_7',
        'f_second_time_from_1',
        'f_second_time_to_1',
        'f_second_time_from_2',
        'f_second_time_to_2',
        'f_second_time_from_3',
        'f_second_time_to_3',
        'f_second_time_from_4',
        'f_second_time_to_4',
        'f_second_time_from_5',
        'f_second_time_to_5',
        'f_second_time_from_6',
        'f_second_time_to_6',
        'f_second_time_from_7',
        'f_second_time_to_7',
        'f_break_time_from_1',
        'f_break_time_to_1',
        'f_break_time_from_2',
        'f_break_time_to_2',
        'f_break_time_from_3',
        'f_break_time_to_3',
        'f_break_time_from_4',
        'f_break_time_to_4',
        'f_break_time_from_5',
        'f_break_time_to_5',
        'f_break_time_from_6',
        'f_break_time_to_6',
        'f_break_time_from_7',
        'f_break_time_to_7',
        'f_eponymo_idiotitas',
        'f_onoma_idiotitas',
        'f_idiotita_idiotitas',
        'f_dieythinsi_idiotitas',
        'f_afm_idiotitas',
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

    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_rel_protocol');
    }

    /**
     * @param string $protocol Protocol of the original E3.5 declaration
     */
    public function setRelatedProtocol(string $protocol): static
    {
        return $this->set('f_rel_protocol', $protocol);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_rel_date');
    }

    /**
     * @param DateTime|string $date Submission date of the original E3.5 declaration
     */
    public function setRelatedDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_rel_date', $date);
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

    public function getChangeDate(): ?string
    {
        return $this->get('f_trpopoihsh_date');
    }

    /**
     * @param DateTime|string $date Date the new schedule applies from
     */
    public function setChangeDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_trpopoihsh_date', $date);
    }
}
