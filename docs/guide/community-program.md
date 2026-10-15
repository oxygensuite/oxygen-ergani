# Community-Benefit Program Employment (E3.1)

The E3.1 form announces the employment of a beneficiary of a community-benefit program (κοινωφελούς χαρακτήρα), for example DYPA community work programs run by municipalities and public bodies.

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `CommunityProgramEmployment`, `CommunityProgramEmploymentCorrection` |
| Action Codes | `WebE31` (declaration), `WebE31C` (correction) |
| Declaration Model | `CommunityProgramDeclaration` |
| Schema | `E31_v1.xsd` (root `AnaggeliesE31` / `AnaggeliaE31`) |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmployment;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;

$declaration = CommunityProgramDeclaration::make()
    // Submission & provider
    ->setBranchCode(0)
    ->setDypaServiceCode('101213')
    ->setLaborInspectionCode('24010')
    ->setProgramCode('363201-24')
    ->setEmployerOrganization('ΔΗΜΟΣ')
    ->setMainActivityCode('8411')
    ->setBranchActivityCode('8411')
    ->setMunicipalityCode('91860101')
    // Beneficiary
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setSex(Sex::MALE)
    ->setBirthDate('08/09/1990')
    ->setNationality('048')
    ->setIdType('ΔΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setMaritalStatus(MaritalStatus::SINGLE)
    ->setNumberOfChildren(0)
    ->setAfm('123456789')
    ->setAmka('08099012345')
    ->setEducationLevel('5')
    // Placement
    ->setPlacementDate('15/09/2025')
    ->setPlacementTime('08:00')
    ->setScheduleDescription('ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 08:00 - 16:00')
    ->setWeeklyHours('40')
    ->setScheduleAttached(false)
    ->setProgramSpecialtyCode('101ΥΕ16')
    ->setSpecialtyCode('962990')
    ->setExperienceYears(0)
    ->setGrossSalary(1000.00)
    ->setHourlyWage(10.00)
    ->setFirstEmployment(false)
    ->setEmploymentType(EmploymentType::FIXED_TERM)
    ->setFixedTermPeriod('15/09/2025', '14/05/2026')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setDypaReferral(true)
    ->setReplacement(false)
    ->setReceivesUnemploymentBenefit(false)
    // Sign-off
    ->setLegalRepresentativeAfm('078776545')
    ->setCertifierLastName('ΛΟΓΙΣΤΗΣ')
    ->setCertifierFirstName('ΜΑΡΙΑ')
    ->setCertifierCapacity('ΛΟΓΙΣΤΡΙΑ')
    ->setCertifierAddress('ΣΤΑΔΙΟΥ 10')
    ->setCertifierAfm('000000001')
    ->withDefaults(); // send every remaining optional field as an empty string

$response = (new CommunityProgramEmployment())->handle($declaration);
```

::: tip All fields are sent
ERGANI expects every E3.1 element to be present, even when empty. Call `withDefaults()` after setting your values to fill the remaining fields with empty strings.
:::

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

$responses = $ergani->sendCommunityProgramEmployment($declaration);
```

## Corrections (WebE31C)

Reference the original submission with `setRelatedProtocol()` / `setRelatedDate()` (fields `f_protocol_sx` / `f_date_ypovolis_sx`) and submit with the correction document:

```php
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmploymentCorrection;

$declaration
    ->setRelatedProtocol($original[0]->protocol)
    ->setRelatedDate($original[0]->submissionDate);

$response = (new CommunityProgramEmploymentCorrection())->handle($declaration);

// or
$responses = $ergani->sendCommunityProgramEmploymentCorrection($declaration);
```

## Helpers

```php
// Fixed-term period (f_orismenou_apo / f_orismenou_ews)
$declaration->setFixedTermPeriod('15/09/2025', '14/05/2026');

// Vocational training period (f_mea_education_date_from / _to)
$declaration->setTrainingPeriod('08/01/2024', '08/09/2024');

// Up to four foreign language codes (f_mea_fl1..f_mea_fl4)
$declaration->setForeignLanguages(['001', '002']);
$declaration->getForeignLanguages(); // ['001', '002']

// Replacement of a beneficiary of the same program
$declaration->replacesBeneficiary('123456789', '01010101010');

// Beneficiary receives DYPA unemployment benefit
$declaration->receivesUnemploymentBenefitFrom('101213');
```

::: info Weekly hours are free text
In E3.1, `f_week_hours` is a free-text field (max 200 characters), so `setWeeklyHours()` accepts `int|string` and sends the value as-is (e.g. `'40'`). The E3.2 form uses a Greek decimal instead.
:::

## Field Reference

"Required" reflects the elements the XSD marks as non-nillable.

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_protocol_sx` | string | No | Protocol of the original declaration (corrections only). |
| `setRelatedDate()` | `f_date_ypovolis_sx` | DateTime\|string | No | Submission date of the original declaration (corrections only). |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code. |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits). |
| `setProgramCode()` | `f_programma_benefit` | string | Yes | Program code (e.g. 363201-24). |
| `setEmployerOrganization()` | `f_mer_organization` | string | No | Employer organization |
| `setMainActivityCode()` | `f_mer_stakod1` | string | Yes | Main activity code (KAD, 4 digits). |
| `setSecondaryActivityCode1()` | `f_mer_stakod2` | string | No | Secondary activity code1 |
| `setSecondaryActivityCode2()` | `f_mer_stakod3` | string | No | Secondary activity code2 |
| `setSecondaryActivityCode3()` | `f_mer_stakod4` | string | No | Secondary activity code3 |
| `setSecondaryActivityCode4()` | `f_mer_stakod5` | string | No | Secondary activity code4 |
| `setBranchActivityCode()` | `f_mpa_stakod` | string | Yes | Activity code (KAD) of the branch. |
| `setMunicipalityCode()` | `f_mpa_kallikratis` | string | Yes | Kallikratis code of the branch (8 digits). |
| `setLastName()` | `f_mea_eponimo` | string | Yes | Last name |
| `setFirstName()` | `f_mea_onoma` | string | Yes | First name |
| `setFatherLastName()` | `f_mea_eponimo_patera` | string | No | Father last name |
| `setFatherName()` | `f_mea_onoma_patera` | string | Yes | Father name |
| `setMotherLastName()` | `f_mea_eponimo_miteras` | string | No | Mother last name |
| `setMotherName()` | `f_mea_onoma_miteras` | string | Yes | Mother name |
| `setSex()` | `f_mea_sex` | Sex\|string\|int | Yes | 0=Male, 1=Female (or use Sex enum) |
| `setBirthPlace()` | `f_mea_birth_city` | string | No | Birth place |
| `setBirthDate()` | `f_mea_date_birth` | DateTime\|string | Yes | Birth date (DD/MM/YYYY) |
| `setNationality()` | `f_mea_nationality` | string | Yes | Nationality code (3 digits, see ParameterLookup::NATIONALITY). |
| `setIdType()` | `f_mea_typos_taytotitas` | string | Yes | Identity document type (see ParameterLookup::ID_TYPE). |
| `setIdNumber()` | `f_mea_ar_taytotitas` | string | Yes | Identity document number |
| `setIdIssuingAuthority()` | `f_mea_ekdousa` | string | No | Id issuing authority |
| `setIdIssueDate()` | `f_mea_date_ekdosis` | DateTime\|string | No | Identity document issue date (DD/MM/YYYY) |
| `setIdExpiryDate()` | `f_mea_date_ekdosis_lixi` | DateTime\|string | No | Identity document expiry date (DD/MM/YYYY) |
| `setResidencePermit()` | `f_mea_res_permit` | string\|bool | No | Whether the beneficiary holds a residence or work permit. |
| `setResidencePermitType()` | `f_mea_res_permit_type` | string | No | Residence permit type (see ParameterLookup::RESIDENCE_PERMIT). |
| `setResidencePermitNumber()` | `f_mea_res_permit_ar` | string | No | Residence permit number |
| `setResidencePermitExpiry()` | `f_mea_res_permit_lixi` | DateTime\|string | No | Residence permit expiry date (DD/MM/YYYY) |
| `setMaritalStatus()` | `f_mea_marital_status` | MaritalStatus\|string\|int | Yes | 0=Single, 1=Married, 2=Divorced, 3=Widowed (or use MaritalStatus enum) |
| `setNumberOfChildren()` | `f_mea_ar_teknon` | int\|string | Yes | Number of children |
| `setAfm()` | `f_mea_afm` | string | Yes | Beneficiary tax ID (AFM) |
| `setTaxOffice()` | `f_mea_doy` | string | No | Tax office code (4 digits, see ParameterLookup::DOY). |
| `setInsuranceAgency()` | `f_mea_insurance_agency` | string | No | Main insurance agency code (see ParameterLookup::PRIMARY_INSURANCE). |
| `setAmika()` | `f_mea_am_ika` | string | No | e-EFKA insurance registry number. |
| `setAmka()` | `f_mea_amka` | string | Yes | Beneficiary social security number (AMKA) |
| `setUnemploymentCardNumber()` | `f_mea_code_anergias` | string | No | Unemployment card number |
| `setAddress()` | `f_mea_dieythinsi` | string | No | Address |
| `setResidenceMunicipalityCode()` | `f_mea_kallikratis` | string | No | Kallikratis code of the beneficiary residence (8 digits). |
| `setPostalCode()` | `f_mea_tk` | string | No | Postal code |
| `setPhone()` | `f_mea_til` | string | No | Phone |
| `setFax()` | `f_mea_fax` | string | No | Fax |
| `setEmail()` | `f_mea_email` | string | No | E-mail |
| `setEducationLevel()` | `f_mea_epipedo_morfosis` | string | Yes | Education level code (see ParameterLookup::EDUCATION_LEVEL). |
| `setProfessionalTraining()` | `f_mea_professional_education` | string\|bool | No | Whether the beneficiary has vocational training. |
| `setTrainingSubject()` | `f_mea_expertise_field` | string | No | Training subject |
| `setSubjectArea()` | `f_mea_subject_area` | string | No | Subject area code (see ParameterLookup::SUBJECT_AREA). |
| `setSubjectGroup()` | `f_mea_subject_group` | string | No | Subject group code (see ParameterLookup::SUBJECT_GROUP). |
| `setEducationAgency()` | `f_mea_education_agency` | string | No | Training agency type code (see ParameterLookup::EDUCATION_AGENCY). |
| `setTrainingDateFrom()` | `f_mea_education_date_from` | DateTime\|string | No | Vocational training start date (DD/MM/YYYY) |
| `setTrainingDateTo()` | `f_mea_education_date_to` | DateTime\|string | No | Vocational training end date (DD/MM/YYYY) |
| `setTrainingDuration()` | `f_mea_duration` | int\|string | No | Training duration in hours. |
| `setTrainingYear()` | `f_mea_education_year` | int\|string | No | Year the training was completed. |
| `setForeignLanguage1()` | `f_mea_fl1` | string | No | Foreign language1 |
| `setForeignLanguage2()` | `f_mea_fl2` | string | No | Foreign language2 |
| `setForeignLanguage3()` | `f_mea_fl3` | string | No | Foreign language3 |
| `setForeignLanguage4()` | `f_mea_fl4` | string | No | Foreign language4 |
| `setComputerSkills()` | `f_mea_pc` | string\|bool | No | 0=No, 1=Yes (or boolean) |
| `setComputerSkillsOther()` | `f_mea_pc_other` | string | No | Computer skills other |
| `setPlacementDate()` | `f_date_proslipsis` | DateTime\|string | Yes | Placement date (DD/MM/YYYY) |
| `setPlacementTime()` | `f_date_time_proslipsis` | string | Yes | Work start time on the first day (HH:MM). |
| `setScheduleDescription()` | `f_orario` | string | No | Working / training days and hours as free text (max 200 characters). |
| `setWeeklyHours()` | `f_week_hours` | int\|string | No | Weekly working hours as free text (e.g. "40"). |
| `setBreakDescription()` | `f_orario_dialeima` | string | No | Break hours as free text (max 200 characters). |
| `setScheduleAttached()` | `f_wres_external` | string\|bool | No | Whether the working days and hours are given in an attached file. |
| `setProgramSpecialtyCode()` | `f_eidikothta_programma` | string | Yes | Specialty code of the public call (e.g. 101ΠΕ01). |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |
| `setExperienceYears()` | `f_proipiresia` | int | Yes | Previous experience in years |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Total gross earnings (Greek float) |
| `setHourlyWage()` | `f_hour_apodoxes` | float | Yes | Hourly wage (Greek float) |
| `setFirstEmployment()` | `f_proti_ergasia` | string\|bool | Yes | Whether this is the first employment as a salaried worker. |
| `setEmploymentType()` | `f_sxesh_apasxolhshs` | EmploymentType\|string\|int | Yes | 0=Indefinite, 1=Fixed-term, 2=Project (`BORROWED` not accepted) |
| `setStartDate()` | `f_orismenou_apo` | DateTime\|string | No | Fixed-term contract start date. |
| `setEndDate()` | `f_orismenou_ews` | DateTime\|string | No | Fixed-term contract end date. |
| `setEmploymentStatus()` | `f_kathestos_apasxolhshs` | EmploymentStatus\|string\|int | Yes | 0=Full-time, 1=Part-time, 2=Rotational (`ON_DEMAND` not accepted) |
| `setPlacementAgency()` | `f_foreas_topothetisis` | string | No | Agency where the beneficiary is placed. |
| `setDypaReferral()` | `f_topothetisi_epistoli` | string\|bool | Yes | Whether the placement is made with a DYPA referral letter. |
| `setReplacement()` | `f_replace_programa` | string\|bool | No | Whether the placement replaces a beneficiary of the same program. |
| `setReplacedAfm()` | `f_replace_programa_afm` | string | No | AFM of the replaced beneficiary |
| `setReplacedAmka()` | `f_replace_programa_amka` | string | No | AMKA of the replaced beneficiary |
| `setReceivesUnemploymentBenefit()` | `f_epidoma_anergeias` | string\|bool | Yes | 0=No, 1=Yes (or boolean) |
| `setUnemploymentBenefitServiceCode()` | `f_ypiresia_oaed_anergeias` | string | No | DYPA service paying the unemployment benefit. |
| `setComments()` | `f_comments` | string | No | Comments |
| `setLegalRepresentativeAfm()` | `f_mek_afm` | string | Yes | AFM of the responsible provider / legal representative. |
| `setCertifierLastName()` | `f_eponymo` | string | Yes | Certifier last name |
| `setCertifierFirstName()` | `f_onoma` | string | Yes | Certifier first name |
| `setCertifierCapacity()` | `f_idiotita` | string | Yes | Capacity of the certifying person (e.g. accountant) |
| `setCertifierAddress()` | `f_dieythinsi` | string | Yes | Certifier address |
| `setCertifierAfm()` | `f_afm_bebaiwn` | string | Yes | AFM of the certifying person |
| `setFormFile()` | `f_file` | string | No | Signed form (base64-encoded PDF). |
| `setForeignFile()` | `f_foreign_file` | string | No | Third-country national labour-market access documents (base64-encoded PDF). |

## Response Handling

```php
$response = (new CommunityProgramEmployment())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new CommunityProgramEmployment())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('community-program.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;

$declaration = CommunityProgramDeclaration::factory()->make();

$declaration = CommunityProgramDeclaration::factory()
    ->forBranch(1)
    ->indefinite()                               // no fixed-term dates
    ->partTime('20')
    ->replacing('123456789', '01010101010')
    ->withUnemploymentBenefit('101213')
    ->asCorrection('ΑΚ123', '01/10/2026')
    ->make();
```

## See Also

- [Theoretical Training (E3.2)](/guide/theoretical-training) - Shares the beneficiary and provider fields
- [Internship (E3.5)](/guide/internship)
- [Services & Queries](/guide/services) - Parameter lookups (nationalities, tax offices, languages, ...)
