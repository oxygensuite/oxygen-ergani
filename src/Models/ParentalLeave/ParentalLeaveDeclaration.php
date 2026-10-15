<?php

namespace OxygenSuite\OxygenErgani\Models\ParentalLeave;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\LeaveGrantMethod;
use OxygenSuite\OxygenErgani\Enums\ParentalLeaveCategory;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Parental leave declaration model for E14_v1 schema.
 *
 * Employer declaration of parental leave granted under article 28 of Law 4808/2021.
 *
 * @see xsd/E14_v1.xsd
 */
class ParentalLeaveDeclaration extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected array $defaults = [
        'Amkas' => ['AmkasE14' => []],
        'Dates' => ['DatesE14' => []],
    ];

    /** @var array<string, string> */
    protected array $casts = [
        'f_week_hours' => 'greek_float:1',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_ypiresia_sepe',
        'f_ypiresia_oaed',
        'f_payment_from_forea',
        'f_category',
        'f_afm_ergazom',
        'f_amka_ergazom',
        'f_am_ika_ergazom',
        'f_eponimo',
        'f_onoma',
        'f_date_proslipsis',
        'f_better_setting_exists',
        'f_better_setting_descr',
        'f_kathestos_apasxolisis',
        'f_sxesh_apasxolisis',
        'f_week_days',
        'f_week_hours',
        'f_foreas_kyrias_asfalisis',
        'f_foreas_epikourikhs_asfalisis',
        'f_one_parent',
        'f_one_parent_case',
        'f_tekno_from_polydymh_kyhsh',
        'f_tropos_xorhghshs_adeias',
        'f_date_enarxis_adeias',
        'f_date_lhxhs_adeias',
        'f_comments',
        'f_afm_proswpoy',
        'Amkas',
        'Dates',
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

    public function getLaborInspectionCode(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    /**
     * @param string $code Labour inspectorate service code (5 digits)
     */
    public function setLaborInspectionCode(string $code): static
    {
        return $this->set('f_ypiresia_sepe', $code);
    }

    public function getDypaServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed');
    }

    /**
     * @param string $code DYPA service code (6 digits)
     */
    public function setDypaServiceCode(string $code): static
    {
        return $this->set('f_ypiresia_oaed', $code);
    }

    /**
     * Whether the leave is paid by the entity itself (articles 56 and 60 of Law 4830/2021).
     */
    public function getPaidByEntity(): ?string
    {
        return $this->get('f_payment_from_forea');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setPaidByEntity(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_payment_from_forea', $value);
    }

    public function getCategory(): ?string
    {
        return $this->get('f_category');
    }

    /**
     * @param ParentalLeaveCategory|string $category Employee category when not paid by the entity (or use ParentalLeaveCategory enum)
     */
    public function setCategory(ParentalLeaveCategory|string $category): static
    {
        if ($category instanceof ParentalLeaveCategory) {
            $category = $category->value;
        }

        return $this->set('f_category', $category);
    }

    // ==================== Employee ====================

    public function getAfm(): ?string
    {
        return $this->get('f_afm_ergazom');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm_ergazom', $afm);
    }

    public function getAmka(): ?string
    {
        return $this->get('f_amka_ergazom');
    }

    public function setAmka(string $amka): static
    {
        return $this->set('f_amka_ergazom', $amka);
    }

    public function getAma(): ?string
    {
        return $this->get('f_am_ika_ergazom');
    }

    /**
     * @param string $ama e-EFKA registry number
     */
    public function setAma(string $ama): static
    {
        return $this->set('f_am_ika_ergazom', $ama);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponimo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponimo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getHireDate(): ?string
    {
        return $this->get('f_date_proslipsis');
    }

    public function setHireDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_proslipsis', $date);
    }

    // ==================== Employment ====================

    /**
     * Whether a more favourable arrangement exists (article 28 par. 2 of Law 4808/2021).
     */
    public function getBetterArrangementExists(): ?string
    {
        return $this->get('f_better_setting_exists');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setBetterArrangementExists(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_better_setting_exists', $value);
    }

    public function getBetterArrangementDescription(): ?string
    {
        return $this->get('f_better_setting_descr');
    }

    public function setBetterArrangementDescription(string $description): static
    {
        return $this->set('f_better_setting_descr', $description);
    }

    /**
     * Set a more favourable arrangement and its description in one call.
     */
    public function setBetterArrangement(string $description): static
    {
        return $this->setBetterArrangementExists(true)->setBetterArrangementDescription($description);
    }

    public function getEmploymentStatus(): ?string
    {
        return $this->get('f_kathestos_apasxolisis');
    }

    /**
     * @param EmploymentStatus|string|int $status 0=Full-time, 1=Part-time, 2=Rotational (or use EmploymentStatus enum). Only 0-2 are accepted by ERGANI.
     */
    public function setEmploymentStatus(EmploymentStatus|string|int $status): static
    {
        if ($status instanceof EmploymentStatus) {
            $status = $status->value;
        }

        return $this->set('f_kathestos_apasxolisis', (string) $status);
    }

    public function getEmploymentType(): ?string
    {
        return $this->get('f_sxesh_apasxolisis');
    }

    /**
     * @param EmploymentType|string|int $type 0=Indefinite, 1=Fixed-term, 2=Project (or use EmploymentType enum). Only 0-2 are accepted by ERGANI.
     */
    public function setEmploymentType(EmploymentType|string|int $type): static
    {
        if ($type instanceof EmploymentType) {
            $type = $type->value;
        }

        return $this->set('f_sxesh_apasxolisis', (string) $type);
    }

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

    public function getWeeklyHours(): ?float
    {
        return $this->greekFloat('f_week_hours');
    }

    public function setWeeklyHours(float $hours): static
    {
        return $this->set('f_week_hours', $hours);
    }

    public function getMainInsuranceAgency(): ?string
    {
        return $this->get('f_foreas_kyrias_asfalisis');
    }

    /**
     * @param string $agency Main insurance agency (free text, max 100 characters)
     */
    public function setMainInsuranceAgency(string $agency): static
    {
        return $this->set('f_foreas_kyrias_asfalisis', $agency);
    }

    public function getSupplementaryInsuranceAgency(): ?string
    {
        return $this->get('f_foreas_epikourikhs_asfalisis');
    }

    /**
     * @param string $agency Supplementary insurance agency (free text, max 100 characters)
     */
    public function setSupplementaryInsuranceAgency(string $agency): static
    {
        return $this->set('f_foreas_epikourikhs_asfalisis', $agency);
    }

    // ==================== Leave ====================

    /**
     * Whether the employee is a single parent (article 28 par. 3 of Law 4808/2021).
     */
    public function getSingleParent(): ?string
    {
        return $this->get('f_one_parent');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setSingleParent(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_one_parent', $value);
    }

    public function getSingleParentCase(): ?string
    {
        return $this->get('f_one_parent_case');
    }

    /**
     * @param string $code Single parent case code (see ParameterLookup::SINGLE_PARENT_CASE)
     */
    public function setSingleParentCase(string $code): static
    {
        return $this->set('f_one_parent_case', $code);
    }

    /**
     * Whether the leave is for a child from a multiple birth.
     */
    public function getMultipleBirth(): ?string
    {
        return $this->get('f_tekno_from_polydymh_kyhsh');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setMultipleBirth(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_tekno_from_polydymh_kyhsh', $value);
    }

    public function getGrantMethod(): ?string
    {
        return $this->get('f_tropos_xorhghshs_adeias');
    }

    /**
     * @param LeaveGrantMethod|string $method 0=Continuous, 1=Partial/flexible (or use LeaveGrantMethod enum)
     */
    public function setGrantMethod(LeaveGrantMethod|string $method): static
    {
        if ($method instanceof LeaveGrantMethod) {
            $method = $method->value;
        }

        return $this->set('f_tropos_xorhghshs_adeias', $method);
    }

    public function getLeaveStartDate(): ?string
    {
        return $this->get('f_date_enarxis_adeias');
    }

    public function setLeaveStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_enarxis_adeias', $date);
    }

    public function getLeaveEndDate(): ?string
    {
        return $this->get('f_date_lhxhs_adeias');
    }

    public function setLeaveEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_lhxhs_adeias', $date);
    }

    /**
     * Set the leave period in one call.
     */
    public function setLeavePeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setLeaveStartDate($from)->setLeaveEndDate($to);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    public function getLegalRepresentativeAfm(): ?string
    {
        return $this->get('f_afm_proswpoy');
    }

    public function setLegalRepresentativeAfm(string $afm): static
    {
        return $this->set('f_afm_proswpoy', $afm);
    }

    // ==================== Children ====================

    /**
     * @return array<int, ParentalLeaveChild>
     */
    public function getChildren(): array
    {
        return $this->get('Amkas')['AmkasE14'] ?? [];
    }

    /**
     * @param array<int, ParentalLeaveChild> $children
     */
    public function setChildren(array $children): static
    {
        return $this->set('Amkas', ['AmkasE14' => $children]);
    }

    public function addChild(ParentalLeaveChild $child): static
    {
        $children = $this->getChildren();
        $children[] = $child;

        return $this->setChildren($children);
    }

    // ==================== Leave Dates ====================

    /**
     * @return array<int, ParentalLeaveDate>
     */
    public function getDates(): array
    {
        return $this->get('Dates')['DatesE14'] ?? [];
    }

    /**
     * @param array<int, ParentalLeaveDate> $dates
     */
    public function setDates(array $dates): static
    {
        return $this->set('Dates', ['DatesE14' => $dates]);
    }

    public function addDate(ParentalLeaveDate $date): static
    {
        $dates = $this->getDates();
        $dates[] = $date;

        return $this->setDates($dates);
    }
}
