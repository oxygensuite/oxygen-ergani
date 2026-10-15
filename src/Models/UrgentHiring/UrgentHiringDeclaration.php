<?php

namespace OxygenSuite\OxygenErgani\Models\UrgentHiring;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Urgent hiring declaration model for FastAna_v1 schema.
 *
 * Fast hiring announcement used to cover urgent needs
 * ("Πρόσληψη για κάλυψη επειγουσών αναγκών").
 *
 * @see xsd/FastAna_v1.xsd
 */
class UrgentHiringDeclaration extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected array $defaults = [
        'EpikourikiSelections' => ['EpikourikiSelectionsFastAna' => []],
    ];

    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_kad_pararthmatos',
        'f_kallikratis_pararthmatos',
        'f_afm',
        'f_til',
        'f_proslipsidate',
        'f_proslipsitime',
        'f_apoxwrisitime',
        'f_second_working_date',
        'f_second_working_datetime',
        'second_working_datetime_apoxwrisis',
        'f_eidikothta',
        'f_eidikothta_anal',
        'f_proipiresia',
        'f_apodoxes',
        'f_xronos_katavolis_apodoxon',
        'f_topos_ergasias',
        'f_topos_ergasias_comment',
        'f_xaraktirismos',
        'f_efarmostea_sillogiki_simbasi',
        'f_efarmostea_sillogiki_simbasi_comments',
        'f_kyria_asfalisi',
        'EpikourikiSelections',
        'f_week_days',
        'f_dialeimma_minutes',
        'f_dialeimma_entos_wrariou',
        'f_euelikto_wrario_minutes',
        'f_comments',
    ];

    // ==================== Submission ====================

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
     * @param string $protocol Protocol of the declaration being corrected (corrections only)
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
     * @param DateTime|string $date Submission date of the declaration being corrected (corrections only)
     */
    public function setRelatedDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_rel_date', $date);
    }

    public function getBranchActivityCode(): ?string
    {
        return $this->get('f_kad_pararthmatos');
    }

    /**
     * @param string $code Branch activity code (KAD, 4 digits)
     */
    public function setBranchActivityCode(string $code): static
    {
        return $this->set('f_kad_pararthmatos', $code);
    }

    public function getMunicipalityCode(): ?string
    {
        return $this->get('f_kallikratis_pararthmatos');
    }

    /**
     * @param string $code Kallikratis municipal/local community code (8 digits)
     */
    public function setMunicipalityCode(string $code): static
    {
        return $this->set('f_kallikratis_pararthmatos', $code);
    }

    // ==================== Employee ====================

    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    public function getPhone(): ?string
    {
        return $this->get('f_til');
    }

    /**
     * @param string $phone Employee mobile phone
     */
    public function setPhone(string $phone): static
    {
        return $this->set('f_til', $phone);
    }

    // ==================== Working Days ====================

    public function getHiringDate(): ?string
    {
        return $this->get('f_proslipsidate');
    }

    public function setHiringDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_proslipsidate', $date);
    }

    public function getStartTime(): ?string
    {
        return $this->get('f_proslipsitime');
    }

    /**
     * @param string $time Work start time on the hiring day (HH:MM)
     */
    public function setStartTime(string $time): static
    {
        return $this->set('f_proslipsitime', $time);
    }

    public function getEndTime(): ?string
    {
        return $this->get('f_apoxwrisitime');
    }

    /**
     * @param string $time Work end time on the hiring day (HH:MM)
     */
    public function setEndTime(string $time): static
    {
        return $this->set('f_apoxwrisitime', $time);
    }

    public function getSecondWorkingDate(): ?string
    {
        return $this->get('f_second_working_date');
    }

    public function setSecondWorkingDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_second_working_date', $date);
    }

    public function getSecondStartTime(): ?string
    {
        return $this->get('f_second_working_datetime');
    }

    /**
     * @param string $time Work start time on the second working day (HH:MM)
     */
    public function setSecondStartTime(string $time): static
    {
        return $this->set('f_second_working_datetime', $time);
    }

    public function getSecondEndTime(): ?string
    {
        return $this->get('second_working_datetime_apoxwrisis');
    }

    /**
     * @param string $time Work end time on the second working day (HH:MM)
     */
    public function setSecondEndTime(string $time): static
    {
        return $this->set('second_working_datetime_apoxwrisis', $time);
    }

    /**
     * Set the second working day with its start and end times in one call.
     */
    public function setSecondWorkingDay(DateTime|string $date, string $startTime, string $endTime): static
    {
        return $this->setSecondWorkingDate($date)
            ->setSecondStartTime($startTime)
            ->setSecondEndTime($endTime);
    }

    // ==================== Employment ====================

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta');
    }

    /**
     * @param string $code Specialty code (see ParameterLookup::SPECIALTY)
     */
    public function setSpecialtyCode(string $code): static
    {
        return $this->set('f_eidikothta', $code);
    }

    public function getSpecialtyDescription(): ?string
    {
        return $this->get('f_eidikothta_anal');
    }

    public function setSpecialtyDescription(string $description): static
    {
        return $this->set('f_eidikothta_anal', $description);
    }

    public function getExperienceYears(): ?int
    {
        return $this->int('f_proipiresia');
    }

    public function setExperienceYears(int $years): static
    {
        return $this->set('f_proipiresia', $years);
    }

    public function getGrossSalary(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    /**
     * @param float $amount Total gross earnings
     */
    public function setGrossSalary(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    public function getWagePaymentTime(): ?string
    {
        return $this->get('f_xronos_katavolis_apodoxon');
    }

    /**
     * @param string $time 0=Every day, 1=On the last day
     */
    public function setWagePaymentTime(string $time): static
    {
        return $this->set('f_xronos_katavolis_apodoxon', $time);
    }

    public function getWorkLocation(): ?string
    {
        return $this->get('f_topos_ergasias');
    }

    /**
     * @param WorkLocation|string|int $location 0=Employer's branch, 1=Other (or use WorkLocation enum)
     */
    public function setWorkLocation(WorkLocation|string|int $location): static
    {
        if ($location instanceof WorkLocation) {
            $location = $location->value;
        }

        return $this->set('f_topos_ergasias', (string) $location);
    }

    public function getWorkLocationComment(): ?string
    {
        return $this->get('f_topos_ergasias_comment');
    }

    public function setWorkLocationComment(string $comment): static
    {
        return $this->set('f_topos_ergasias_comment', $comment);
    }

    public function getWorkerType(): ?string
    {
        return $this->get('f_xaraktirismos');
    }

    /**
     * @param WorkerType|string|int $type 0=Blue-collar (worker), 1=White-collar (employee) (or use WorkerType enum)
     */
    public function setWorkerType(WorkerType|string|int $type): static
    {
        if ($type instanceof WorkerType) {
            $type = $type->value;
        }

        return $this->set('f_xaraktirismos', (string) $type);
    }

    public function getCollectiveAgreementApplies(): ?string
    {
        return $this->get('f_efarmostea_sillogiki_simbasi');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setCollectiveAgreementApplies(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_efarmostea_sillogiki_simbasi', $value);
    }

    public function getCollectiveAgreementComment(): ?string
    {
        return $this->get('f_efarmostea_sillogiki_simbasi_comments');
    }

    public function setCollectiveAgreementComment(string $comment): static
    {
        return $this->set('f_efarmostea_sillogiki_simbasi_comments', $comment);
    }

    // ==================== Insurance ====================

    public function getMainInsurance(): ?string
    {
        return $this->get('f_kyria_asfalisi');
    }

    /**
     * @param string $code Main insurance code (see ParameterLookup::PRIMARY_INSURANCE)
     */
    public function setMainInsurance(string $code): static
    {
        return $this->set('f_kyria_asfalisi', $code);
    }

    /**
     * @return array<int, SupplementaryInsuranceSelection>
     */
    public function getSupplementaryInsuranceSelections(): array
    {
        return $this->get('EpikourikiSelections')['EpikourikiSelectionsFastAna'] ?? [];
    }

    /**
     * @param array<int, SupplementaryInsuranceSelection> $selections
     */
    public function setSupplementaryInsuranceSelections(array $selections): static
    {
        return $this->set('EpikourikiSelections', ['EpikourikiSelectionsFastAna' => $selections]);
    }

    /**
     * @param SupplementaryInsuranceSelection|string $selection Selection or supplementary insurance code
     *                                                          (see ParameterLookup::SUPPLEMENTARY_INSURANCE)
     */
    public function addSupplementaryInsurance(SupplementaryInsuranceSelection|string $selection): static
    {
        if (is_string($selection)) {
            $selection = SupplementaryInsuranceSelection::make()->setSupplementaryInsuranceCode($selection);
        }

        $selections = $this->getSupplementaryInsuranceSelections();
        $selections[] = $selection;

        return $this->setSupplementaryInsuranceSelections($selections);
    }

    // ==================== Schedule ====================

    public function getWeekDays(): ?string
    {
        return $this->get('f_week_days');
    }

    /**
     * @param WeekDays|string|int $days 5=Five-day week, 6=Six-day week (or use WeekDays enum)
     */
    public function setWeekDays(WeekDays|string|int $days): static
    {
        if ($days instanceof WeekDays) {
            $days = $days->value;
        }

        return $this->set('f_week_days', (string) $days);
    }

    public function getBreakMinutes(): ?int
    {
        return $this->int('f_dialeimma_minutes');
    }

    /**
     * @param int $minutes Break duration in minutes (1-3 digits)
     */
    public function setBreakMinutes(int $minutes): static
    {
        return $this->set('f_dialeimma_minutes', $minutes);
    }

    public function getBreakWithinSchedule(): ?string
    {
        return $this->get('f_dialeimma_entos_wrariou');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setBreakWithinSchedule(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_dialeimma_entos_wrariou', $value);
    }

    public function getFlexibleArrivalMinutes(): ?int
    {
        return $this->int('f_euelikto_wrario_minutes');
    }

    /**
     * @param int $minutes Flexible arrival allowance in minutes (1-3 digits)
     */
    public function setFlexibleArrivalMinutes(int $minutes): static
    {
        return $this->set('f_euelikto_wrario_minutes', $minutes);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }
}
