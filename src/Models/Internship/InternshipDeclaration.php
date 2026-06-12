<?php

namespace OxygenSuite\OxygenErgani\Models\Internship;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Internship declaration model for E35_v2 schema.
 *
 * Used for reporting internship start or modification to ERGANI.
 *
 * @see xsd/E35_v2.xsd
 */
class InternshipDeclaration extends Model
{
    use HasFactory;
    /** @var array<string, string> */
    protected array $casts = [
        'f_week_hours' => 'greek_float:1',
        'f_total_hours' => 'greek_float:1',
        'f_apodoxes' => 'greek_float',
        'f_hour_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_ypiresia_sepe',
        'f_ypiresia_oaed',
        'f_ergodotikh_organwsh',
        'f_kad_kyria',
        'f_kad_deyt_1',
        'f_kad_deyt_2',
        'f_kad_deyt_3',
        'f_kad_deyt_4',
        'f_kad_pararthmatos',
        'f_kallikratis_pararthmatos',
        'f_eponymo',
        'f_onoma',
        'f_onoma_patros',
        'f_onoma_mitros',
        'f_topos_gennhshs',
        'f_birthdate',
        'f_sex',
        'f_yphkoothta',
        'f_typos_taytothtas',
        'f_ar_taytothtas',
        'f_ekdousa_arxh',
        'f_date_ekdosis',
        'f_date_ekdosis_lixi',
        'f_res_permit_int',
        'f_res_permit_int_type',
        'f_res_permit_int_ar',
        'f_res_permit_int_lixi',
        'f_marital_status',
        'f_arithmos_teknon',
        'f_afm',
        'f_doy',
        'f_amika',
        'f_amka',
        'f_ar_vivliou_anilikou',
        'f_til',
        'f_email',
        'f_epipedo_morfosis',
        'f_educational_institute_nationality',
        'f_educational_institute_name',
        'f_sxolh',
        'f_department',
        'f_approval_number',
        'f_date_proslipsis',
        'f_date_time_proslipsis',
        'f_week_hours',
        'f_total_hours',
        'f_eidikothta',
        'f_apodoxes',
        'f_hour_apodoxes',
        'f_orismenou_apo',
        'f_orismenou_ews',
        'f_topothetisioaed',
        'f_comments',
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

    public function setRelatedProtocol(string $protocol): static
    {
        return $this->set('f_rel_protocol', $protocol);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_rel_date');
    }

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

    public function setLaborInspectionCode(string $code): static
    {
        return $this->set('f_ypiresia_sepe', $code);
    }

    public function getDypaServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed');
    }

    public function setDypaServiceCode(string $code): static
    {
        return $this->set('f_ypiresia_oaed', $code);
    }

    public function getEmployerOrganization(): ?string
    {
        return $this->get('f_ergodotikh_organwsh');
    }

    public function setEmployerOrganization(string $organization): static
    {
        return $this->set('f_ergodotikh_organwsh', $organization);
    }

    public function getMainActivityCode(): ?string
    {
        return $this->get('f_kad_kyria');
    }

    public function setMainActivityCode(string $code): static
    {
        return $this->set('f_kad_kyria', $code);
    }

    public function getSecondaryActivityCode1(): ?string
    {
        return $this->get('f_kad_deyt_1');
    }

    public function setSecondaryActivityCode1(string $code): static
    {
        return $this->set('f_kad_deyt_1', $code);
    }

    public function getSecondaryActivityCode2(): ?string
    {
        return $this->get('f_kad_deyt_2');
    }

    public function setSecondaryActivityCode2(string $code): static
    {
        return $this->set('f_kad_deyt_2', $code);
    }

    public function getSecondaryActivityCode3(): ?string
    {
        return $this->get('f_kad_deyt_3');
    }

    public function setSecondaryActivityCode3(string $code): static
    {
        return $this->set('f_kad_deyt_3', $code);
    }

    public function getSecondaryActivityCode4(): ?string
    {
        return $this->get('f_kad_deyt_4');
    }

    public function setSecondaryActivityCode4(string $code): static
    {
        return $this->set('f_kad_deyt_4', $code);
    }

    public function getBranchActivityCode(): ?string
    {
        return $this->get('f_kad_pararthmatos');
    }

    public function setBranchActivityCode(string $code): static
    {
        return $this->set('f_kad_pararthmatos', $code);
    }

    public function getMunicipalityCode(): ?string
    {
        return $this->get('f_kallikratis_pararthmatos');
    }

    public function setMunicipalityCode(string $code): static
    {
        return $this->set('f_kallikratis_pararthmatos', $code);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getFatherName(): ?string
    {
        return $this->get('f_onoma_patros');
    }

    public function setFatherName(string $fatherName): static
    {
        return $this->set('f_onoma_patros', $fatherName);
    }

    public function getMotherName(): ?string
    {
        return $this->get('f_onoma_mitros');
    }

    public function setMotherName(string $motherName): static
    {
        return $this->set('f_onoma_mitros', $motherName);
    }

    public function getBirthPlace(): ?string
    {
        return $this->get('f_topos_gennhshs');
    }

    public function setBirthPlace(string $birthPlace): static
    {
        return $this->set('f_topos_gennhshs', $birthPlace);
    }

    public function getBirthDate(): ?string
    {
        return $this->get('f_birthdate');
    }

    public function setBirthDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_birthdate', $date);
    }

    public function getSex(): ?string
    {
        return $this->get('f_sex');
    }

    /**
     * @param Sex|string|int $sex 0=Male, 1=Female (or use Sex enum)
     */
    public function setSex(Sex|string|int $sex): static
    {
        if ($sex instanceof Sex) {
            $sex = $sex->value;
        }

        return $this->set('f_sex', (string) $sex);
    }

    public function getNationality(): ?string
    {
        return $this->get('f_yphkoothta');
    }

    public function setNationality(string $nationality): static
    {
        return $this->set('f_yphkoothta', $nationality);
    }

    public function getIdType(): ?string
    {
        return $this->get('f_typos_taytothtas');
    }

    public function setIdType(string $idType): static
    {
        return $this->set('f_typos_taytothtas', $idType);
    }

    public function getIdNumber(): ?string
    {
        return $this->get('f_ar_taytothtas');
    }

    public function setIdNumber(string $idNumber): static
    {
        return $this->set('f_ar_taytothtas', $idNumber);
    }

    public function getIdIssuingAuthority(): ?string
    {
        return $this->get('f_ekdousa_arxh');
    }

    public function setIdIssuingAuthority(string $authority): static
    {
        return $this->set('f_ekdousa_arxh', $authority);
    }

    public function getIdIssueDate(): ?string
    {
        return $this->get('f_date_ekdosis');
    }

    public function setIdIssueDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_ekdosis', $date);
    }

    public function getIdExpiryDate(): ?string
    {
        return $this->get('f_date_ekdosis_lixi');
    }

    public function setIdExpiryDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_ekdosis_lixi', $date);
    }

    public function getResidencePermit(): ?string
    {
        return $this->get('f_res_permit_int');
    }

    public function setResidencePermit(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_res_permit_int', $value);
    }

    public function getResidencePermitType(): ?string
    {
        return $this->get('f_res_permit_int_type');
    }

    public function setResidencePermitType(string $type): static
    {
        return $this->set('f_res_permit_int_type', $type);
    }

    public function getResidencePermitNumber(): ?string
    {
        return $this->get('f_res_permit_int_ar');
    }

    public function setResidencePermitNumber(string $number): static
    {
        return $this->set('f_res_permit_int_ar', $number);
    }

    public function getResidencePermitExpiry(): ?string
    {
        return $this->get('f_res_permit_int_lixi');
    }

    public function setResidencePermitExpiry(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_res_permit_int_lixi', $date);
    }

    public function getMaritalStatus(): ?string
    {
        return $this->get('f_marital_status');
    }

    /**
     * @param MaritalStatus|string|int $status 0=Single, 1=Married, 2=Divorced, 3=Widowed (or use MaritalStatus enum)
     */
    public function setMaritalStatus(MaritalStatus|string|int $status): static
    {
        if ($status instanceof MaritalStatus) {
            $status = $status->value;
        }

        return $this->set('f_marital_status', (string) $status);
    }

    public function getNumberOfChildren(): ?string
    {
        return $this->get('f_arithmos_teknon');
    }

    public function setNumberOfChildren(int|string $count): static
    {
        return $this->set('f_arithmos_teknon', (string) $count);
    }

    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    public function getTaxOffice(): ?string
    {
        return $this->get('f_doy');
    }

    public function setTaxOffice(string $doy): static
    {
        return $this->set('f_doy', $doy);
    }

    public function getAmika(): ?string
    {
        return $this->get('f_amika');
    }

    public function setAmika(string $amika): static
    {
        return $this->set('f_amika', $amika);
    }

    public function getAmka(): ?string
    {
        return $this->get('f_amka');
    }

    public function setAmka(string $amka): static
    {
        return $this->set('f_amka', $amka);
    }

    public function getMinorBookNumber(): ?string
    {
        return $this->get('f_ar_vivliou_anilikou');
    }

    public function setMinorBookNumber(string $number): static
    {
        return $this->set('f_ar_vivliou_anilikou', $number);
    }

    public function getPhone(): ?string
    {
        return $this->get('f_til');
    }

    public function setPhone(string $phone): static
    {
        return $this->set('f_til', $phone);
    }

    public function getEmail(): ?string
    {
        return $this->get('f_email');
    }

    public function setEmail(string $email): static
    {
        return $this->set('f_email', $email);
    }

    public function getEducationLevel(): ?string
    {
        return $this->get('f_epipedo_morfosis');
    }

    public function setEducationLevel(string $level): static
    {
        return $this->set('f_epipedo_morfosis', $level);
    }

    public function getInstituteNationality(): ?string
    {
        return $this->get('f_educational_institute_nationality');
    }

    public function setInstituteNationality(string $nationality): static
    {
        return $this->set('f_educational_institute_nationality', $nationality);
    }

    public function getInstituteName(): ?string
    {
        return $this->get('f_educational_institute_name');
    }

    public function setInstituteName(string $name): static
    {
        return $this->set('f_educational_institute_name', $name);
    }

    public function getSchool(): ?string
    {
        return $this->get('f_sxolh');
    }

    public function setSchool(string $school): static
    {
        return $this->set('f_sxolh', $school);
    }

    public function getDepartment(): ?string
    {
        return $this->get('f_department');
    }

    public function setDepartment(string $department): static
    {
        return $this->set('f_department', $department);
    }

    public function getApprovalNumber(): ?string
    {
        return $this->get('f_approval_number');
    }

    public function setApprovalNumber(string $number): static
    {
        return $this->set('f_approval_number', $number);
    }

    public function getPlacementDate(): ?string
    {
        return $this->get('f_date_proslipsis');
    }

    public function setPlacementDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_proslipsis', $date);
    }

    public function getPlacementTime(): ?string
    {
        return $this->get('f_date_time_proslipsis');
    }

    public function setPlacementTime(string $time): static
    {
        return $this->set('f_date_time_proslipsis', $time);
    }

    public function getWeeklyHours(): ?float
    {
        return $this->greekFloat('f_week_hours');
    }

    public function setWeeklyHours(float $hours): static
    {
        return $this->set('f_week_hours', $hours);
    }

    public function getTotalHours(): ?float
    {
        return $this->greekFloat('f_total_hours');
    }

    public function setTotalHours(float $hours): static
    {
        return $this->set('f_total_hours', $hours);
    }

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta');
    }

    public function setSpecialtyCode(string $code): static
    {
        return $this->set('f_eidikothta', $code);
    }

    public function getCompensation(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    public function setCompensation(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    public function getHourlyCompensation(): ?float
    {
        return $this->greekFloat('f_hour_apodoxes');
    }

    public function setHourlyCompensation(float $amount): static
    {
        return $this->set('f_hour_apodoxes', $amount);
    }

    public function getStartDate(): ?string
    {
        return $this->get('f_orismenou_apo');
    }

    public function setStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_apo', $date);
    }

    public function getEndDate(): ?string
    {
        return $this->get('f_orismenou_ews');
    }

    public function setEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_orismenou_ews', $date);
    }

    public function getDypaPlacement(): ?string
    {
        return $this->get('f_topothetisioaed');
    }

    public function setDypaPlacement(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_topothetisioaed', $value);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * Set primary schedule for a day (1=Monday, 7=Sunday).
     */
    public function setSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_time_from_{$day}", $from)
            ->set("f_time_to_{$day}", $to);
    }

    /**
     * Set split-shift schedule for a day (1=Monday, 7=Sunday).
     */
    public function setSplitSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_second_time_from_{$day}", $from)
            ->set("f_second_time_to_{$day}", $to);
    }

    /**
     * Set break schedule for a day (1=Monday, 7=Sunday).
     */
    public function setBreakSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_break_time_from_{$day}", $from)
            ->set("f_break_time_to_{$day}", $to);
    }

    public function getCertifierLastName(): ?string
    {
        return $this->get('f_eponymo_idiotitas');
    }

    public function setCertifierLastName(string $lastName): static
    {
        return $this->set('f_eponymo_idiotitas', $lastName);
    }

    public function getCertifierFirstName(): ?string
    {
        return $this->get('f_onoma_idiotitas');
    }

    public function setCertifierFirstName(string $firstName): static
    {
        return $this->set('f_onoma_idiotitas', $firstName);
    }

    public function getCertifierCapacity(): ?string
    {
        return $this->get('f_idiotita_idiotitas');
    }

    public function setCertifierCapacity(string $capacity): static
    {
        return $this->set('f_idiotita_idiotitas', $capacity);
    }

    public function getCertifierAddress(): ?string
    {
        return $this->get('f_dieythinsi_idiotitas');
    }

    public function setCertifierAddress(string $address): static
    {
        return $this->set('f_dieythinsi_idiotitas', $address);
    }

    public function getCertifierAfm(): ?string
    {
        return $this->get('f_afm_idiotitas');
    }

    public function setCertifierAfm(string $afm): static
    {
        return $this->set('f_afm_idiotitas', $afm);
    }

    public function getLegalRepresentativeAfm(): ?string
    {
        return $this->get('f_afm_proswpoy');
    }

    public function setLegalRepresentativeAfm(string $afm): static
    {
        return $this->set('f_afm_proswpoy', $afm);
    }

    public function getFormFile(): ?string
    {
        return $this->get('f_file');
    }

    public function setFormFile(string $base64): static
    {
        return $this->set('f_file', $base64);
    }

    public function getForeignFile(): ?string
    {
        return $this->get('f_foreign_file');
    }

    public function setForeignFile(string $base64): static
    {
        return $this->set('f_foreign_file', $base64);
    }

    public function getMinorFile(): ?string
    {
        return $this->get('f_young_file');
    }

    public function setMinorFile(string $base64): static
    {
        return $this->set('f_young_file', $base64);
    }
}
